<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Passenger;
use App\Mail\SendInfoSale;
use App\Models\CartProduct;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Enums\OrderStatusEnum;
use App\Mail\SendInfoSaleUser;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Exceptions\MPApiException;
use App\Http\Requests\Payment\PaymentRequest;
use MercadoPago\Client\Preference\PreferenceClient;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class PaymentController extends Controller
{
	//----------------------------------------------------------------------------------------------
	//PAYPAL

	public function createPaypalRecharge(PaymentRequest $request)
	{
		$user = auth('sanctum')->user();
		$product = Product::find($request->input('productId'));
		$value = $product->price;
		if($product->discount){
			$value = $product->price -
			$product->price * ($product->discount / 100);
		}

		$response = Http::get('https://api.exchangerate-api.com/v4/latest/USD');
		$exchangeRates = $response->json();
		if ($response->successful()) {
			$usdToCop = $exchangeRates['rates']['COP'];
			$convertedAmount = $value / $usdToCop;
			$convertedAmount = round($convertedAmount, 1);
			$params = ['value' => $value, 'billingDetails' => $request->input('billingDetails'), 'userId' => $user->id, 'productId' => $product->id];
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

			log::info($response);

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

			Log::info('CHECKPOINT');
			Log::info($billingDetails);

			$order = Order::create(
				[
					'status' => OrderStatusEnum::RESERVED->name,
					'paymentId'   => $payment->id,
					'userId' => $user->id,
					'date'   => $billingDetails['date'],
					'emergencyContactName'   => $billingDetails['emergencyContactName'],
					'emergencyContactPhone'   => $billingDetails['emergencyContactPhone'],
				]
			);



			$passengers = $billingDetails['passengers'];

			Log::info($passengers);

			foreach($passengers as $passenger){
				Passenger::create(
					[
						'name' => $passenger['name'],
						'birthdate' => $passenger['birthdate'],
						'email' => $passenger['email'],
						'phone' => $passenger['phone'],
						'idType' => $passenger['idType'],
						'identification' => $passenger['identification'],
						'gender' => $passenger['gender'],
						'orderId' => $order->id,
					]);
			}

			$product = Product::find($request->input('productId'));
			$product->update([
				'availableQuantity' => $product->availableQuantity - 1
			]);


			$clientRedirect = 'http://localhost:4200';
			$redirectUrl = $clientRedirect . 'products/orders';
			return redirect()->to($redirectUrl);
		} else {
			return redirect()->route('common-cancel-recharge');
		}
	} //end createSubscription()






	protected function commonCancelRecharge()
	{
		$clientRedirect = 'https://lazomascotas.com/#/';
		$redirectUrl = $clientRedirect . '/orders';
		return redirect()->to($redirectUrl);
	} //end Cancel Subscription()
}
