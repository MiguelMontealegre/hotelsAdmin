<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\CartProduct;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Enums\OrderStatusEnum;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use MercadoPago\Exceptions\MPApiException;
use App\Http\Requests\Payment\PaymentRequest;
use MercadoPago\Client\Preference\PreferenceClient;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendInfoSaleUser;
use App\Mail\SendInfoSale;


class PaymentController extends Controller
{
	//----------------------------------------------------------------------------------------------
	//PAYPAL

	public function createPaypalRecharge(PaymentRequest $request)
	{
		$user = auth('sanctum')->user();
		$cupon = null;
		$cuponProducts = [];
		$cuponCategories = [];
		if($request->has('cuponId')){
			$cuponId = $request->input('cuponId');
			$cupon = Cupon::find($cuponId);
			$cuponProducts = $cupon->products;
			$cuponCategories = $cupon->categories;
		}
		$value = CartProduct::where('userId', $user->id)
			->with('product')
			->get()
			->sum(function ($cartProduct) use ($user, $cupon, $cuponProducts, $cuponCategories) {


				foreach($cuponCategories as $cuponCategory){
					foreach($cartProduct->product->categories as $productCategory) {
						if($cuponCategory->id === $productCategory->id){
							$cartProduct->product->discount += $cupon->discount;
							$cartProduct->product->wholesaleDiscount += $cupon->discount;
							$cartProduct->cuponValidate = true;
						}
					}
				}
				if(!$cartProduct->cuponValidate){
					foreach($cuponProducts as $cuponProduct) {
						if($cuponProduct->id === $cartProduct->product->id){
							$cartProduct->product->discount += $cupon->discount;
							$cartProduct->product->wholesaleDiscount += $cupon->discount;
						}
					}
				}


				if ($cartProduct->quantity >= $cartProduct->product->wholesaleMinQuantity && $cartProduct->product->wholesalePrice && isset($user->wholesaleUsers) && isset($user->wholesaleUsers->isApproved)) {
					if ($cartProduct->product->wholesaleDiscount > 0) {
						return ($cartProduct->product->wholesalePrice -
							$cartProduct->product->wholesalePrice *
							($cartProduct->product->wholesaleDiscount / 100)) * $cartProduct->quantity;
					} else {
						return $cartProduct->product->wholesalePrice * $cartProduct->quantity;
					}
				}
				if ($cartProduct->product->discount > 0) {
					return ($cartProduct->product->price -
						$cartProduct->product->price *
						($cartProduct->product->discount / 100)) * $cartProduct->quantity;
				} else {
					return $cartProduct->product->price * $cartProduct->quantity;
				}
			});

		$response = Http::get('https://api.exchangerate-api.com/v4/latest/USD');
		$exchangeRates = $response->json();
		if ($response->successful()) {
			$usdToCop = $exchangeRates['rates']['COP'];
			$convertedAmount = $value / $usdToCop;
			$convertedAmount = round($convertedAmount, 1);
			$params = ['value' => $value, 'billingDetails' => $request->input('billingDetails'), 'userId' => $user->id, 'cuponId' => $cupon ? $cupon->id : null];
			$provider = new PayPalClient;
			$provider->setApiCredentials(config('paypal'));
			$provider->getAccessToken();
			$response = $provider->createOrder([
				'intent' => 'CAPTURE',
				'application_context' => [
					'return_url' => route('paypal-recharge-success', $params),
					'cancel_url' => route('common-cancel-recharge', $params)
				],
				'purchase_units' => [
					[
						'amount' => [
							'currency_code' => 'USD',
							'value' => $convertedAmount
						]
					]
				]
			]);

			if (isset($response['id']) && $response['id'] !== null) {
				foreach ($response['links'] as $link) {
					if ($link['rel'] === 'approve') {
						return response()
							->json($link['href'])
							->setStatusCode(ResponseAlias::HTTP_OK);
					}
				}
			} else {
				return response()->json(['error' => 'Something went wrong 22'], 500);
			}
		} else {
			return response()->json(['error' => 'Failed to fetch exchange rates'], 500);
		}
	}


	/**
	 * Create Subscription
	 *
	 * @param  Request $request
	 */
	protected function paypalRechargeSuccess(Request $request)
	{
		$provider = new PayPalClient;
		$provider->setApiCredentials(config('paypal'));
		$provider->getAccessToken();
		$response = $provider->capturePaymentOrder($request->token);

		if (isset($response['status']) && $response['status'] == 'COMPLETED') {
			$user = User::find($request->input('userId'));
			$payment = Payment::create(
				[
					'userId'   => $user->id,
					'value'   => $request->input('value'),
					'provider' => 'paypal'
				]
			);
			$billingDetails = $request->input('billingDetails');
			$order = Order::create(
				[
					'status' => OrderStatusEnum::STORE->name,
					'paymentId'   => $payment->id,
					'userId' => $user->id,
					'firstName'   => $billingDetails['firstName'],
					'lastName'   => $billingDetails['lastName'],
					'address'   => $billingDetails['address'],
					'addressOptional'   => $billingDetails['addressOptional'] ?? null,
					'city'   => $billingDetails['city'],
					'country'   => $billingDetails['country'],
					'postalCode'   => $billingDetails['postalCode'],
					'optional'   => $billingDetails['optional'] ?? null,
				]
			);
			$cartProducts = CartProduct::where('userId', $user->id)->get();
			foreach($cartProducts as $cartProduct){
				OrderProduct::create(
					[
						'quantity' => $cartProduct->quantity,
						'sizeId' => $cartProduct->sizeId,
						'colorId' => $cartProduct->colorId,
						'productId' => $cartProduct->productId,
						'orderId' => $order->id
					]);
				$product = Product::find($cartProduct->productId);
				$product->update([
					'availableQuantity' => $product->availableQuantity - $cartProduct->quantity
				]);
			}
			$cuponId = $request->input('cuponId');
			if($cuponId){
				$cupon = Cupon::find($cuponId);
				$cupon->update([
					'availableQuantity' => $cupon->availableQuantity - 1
				]);
			}

			$recipientEmails = User::whereHas('roles', function ($query) {
				$query->where('name', 'ADMIN')
				->orWhere('name', 'SALE_USER'); 
			})->pluck('email')->toArray();
			Mail::to($recipientEmails)->send(new SendInfoSale( $user,$order));
			Mail::to($user->email)->send(new SendInfoSaleUser($user, $order));

			CartProduct::where('userId', $user->id)->delete();

			$clientRedirect = 'https://lazomascotas.com/#/';
			$redirectUrl = $clientRedirect . 'products/orders';
			return redirect()->to($redirectUrl);
		} else {
			return redirect()->route('common-cancel-recharge');
		}
	} //end createSubscription()





	//----------------------------------------------------------------------------------------------
	//MERCADOPAGO


	public function createMercadopagoRecharge(Request $request)
	{
		$user = auth('sanctum')->user();
		$cupon = null;
		$cuponProducts = [];
		$cuponCategories = [];
		if($request->has('cuponId')){
			$cuponId = $request->input('cuponId');
			$cupon = Cupon::find($cuponId);
			$cuponProducts = $cupon->products;
			$cuponCategories = $cupon->categories;
		}
		$value = CartProduct::where('userId', $user->id)
			->with('product')
			->get()
			->sum(function ($cartProduct) use ($user, $cupon, $cuponProducts, $cuponCategories) {
				

				foreach($cuponCategories as $cuponCategory){
					foreach($cartProduct->product->categories as $productCategory) {
						if($cuponCategory->id === $productCategory->id){
							$cartProduct->product->discount += $cupon->discount;
							$cartProduct->product->wholesaleDiscount += $cupon->discount;
							$cartProduct->cuponValidate = true;
						}
					}
				}
				if(!$cartProduct->cuponValidate){
					foreach($cuponProducts as $cuponProduct) {
						if($cuponProduct->id === $cartProduct->product->id){
							$cartProduct->product->discount += $cupon->discount;
							$cartProduct->product->wholesaleDiscount += $cupon->discount;
						}
					}
				}

				
				if ($cartProduct->quantity >= $cartProduct->product->wholesaleMinQuantity && $cartProduct->product->wholesalePrice && isset($user->wholesaleUsers) && isset($user->wholesaleUsers->isApproved)) {
					if ($cartProduct->product->wholesaleDiscount > 0) {
						return ($cartProduct->product->wholesalePrice -
							$cartProduct->product->wholesalePrice *
							($cartProduct->product->wholesaleDiscount / 100)) * $cartProduct->quantity;
					} else {
						return $cartProduct->product->wholesalePrice * $cartProduct->quantity;
					}
				}
				if ($cartProduct->product->discount > 0) {
					return ($cartProduct->product->price -
						$cartProduct->product->price *
						($cartProduct->product->discount / 100)) * $cartProduct->quantity;
				} else {
					return $cartProduct->product->price * $cartProduct->quantity;
				}
			});
		$billingDetails = $request->input('billingDetails');
		$params = ['value' => floor($value), 'billingDetails' => $billingDetails, 'userId' => $user->id, 'cuponId' => $cupon ? $cupon->id : null];
		require base_path('vendor/autoload.php');
		MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
		$client = new PreferenceClient();


		$paymentMethods = [
			'excluded_payment_methods' => [],
			'installments' => 1,
			'default_installments' => 1
		];

		$backUrls = array(
			'success' => route('mercadopago-recharge-success', $params),
			'failure' => route('common-cancel-recharge', $params)
		);

		$payer = array(
			'name' => $billingDetails['firstName'] . ' ' . $billingDetails['lastName'],
			'surname' => $billingDetails['firstName'] . $billingDetails['lastName'],
			'email' => $user->email,
		);

		$item = array(
			'id' => uniqid(),
			'title' => 'Productos Seleccionados',
			'description' => 'Compra de productos - lazo pet shop',
			'currency_id' => 'COP',
			'quantity' => 1,
			'unit_price' => floor($value)
		);

		$request = [
			'items' => [$item],
			'payer' => $payer,
			'payment_methods' => $paymentMethods,
			'back_urls' => $backUrls,
			'auto_return' => 'approved',
		];
		try {
			$preference = $client->create($request);
			if (property_exists($preference, 'init_point')) {
				return response()
					->json($preference)
					->setStatusCode(ResponseAlias::HTTP_OK);
			} else {
				return response()->json(['error' => 'Something went wrong 1'], 500);
			}
		} catch (MPApiException $error) {
			log::info($error);
			return response()->json(['error' => 'Something went wrong 555'], 500);
		}
	}


	protected function mercadopagoRechargeSuccess(Request $request)
	{
		$user = User::find($request->input('userId'));
		$billingDetails = $request->input('billingDetails');
		$payment = Payment::create(
			[
				'userId'   => $user->id,
				'value'   => $request->input('value'),
				'provider' => 'paypal'
			]
		);
		$order = Order::create(
			[
				'status' => OrderStatusEnum::STORE->name,
				'paymentId'   => $payment->id,
				'userId' => $user->id,
				'firstName'   => $billingDetails['firstName'],
				'lastName'   => $billingDetails['lastName'],
				'address'   => $billingDetails['address'],
				'addressOptional'   => $billingDetails['addressOptional'] ?? null,
				'city'   => $billingDetails['city'],
				'country'   => $billingDetails['country'],
				'postalCode'   => $billingDetails['postalCode'],
				'optional'   => $billingDetails['optional'] ?? null,
			]
		);
		$cartProducts = CartProduct::where('userId', $user->id)->get();
			foreach($cartProducts as $cartProduct){
				OrderProduct::create(
					[
						'quantity' => $cartProduct->quantity,
						'sizeId' => $cartProduct->sizeId,
						'colorId' => $cartProduct->colorId,
						'productId' => $cartProduct->productId,
						'orderId' => $order->id
					]);
				$product = Product::find($cartProduct->productId);
				$product->update([
					'availableQuantity' => $product->availableQuantity - $cartProduct->quantity
				]);
			}
			$cuponId = $request->input('cuponId');
			if($cuponId){
				$cupon = Cupon::find($cuponId);
				$cupon->update([
					'availableQuantity' => $cupon->availableQuantity - 1
				]);
			}
			
			
			
				$recipientEmails = User::whereHas('roles', function ($query) {
					$query->where('name', 'ADMIN')
					->orWhere('name', 'SALE_USER'); 
				})->pluck('email')->toArray();
				Mail::to($recipientEmails)->send(new SendInfoSale( $user,$order));
				Mail::to($user->email)->send(new SendInfoSaleUser($user, $order));


		CartProduct::where('userId', $user->id)->delete();
			
		$clientRedirect = 'https://lazomascotas.com/#/';
		$redirectUrl = $clientRedirect . 'products/orders';
		return redirect()->to($redirectUrl);
	
		//end createSubscription()

			}


	//----------------------------------------------------------------------------------------------




	protected function commonCancelRecharge()
	{
		$clientRedirect = 'https://lazomascotas.com/#/';
		$redirectUrl = $clientRedirect . '/orders';
		return redirect()->to($redirectUrl);
	} //end Cancel Subscription()
}
