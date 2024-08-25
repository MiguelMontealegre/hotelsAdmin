<?php

declare(strict_types=1);

namespace App\Http\Controllers\CartProduct;

use App\Models\Cupon;
use App\Models\Product;
use App\Models\CartProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\CartProduct\CartProductRequest;



class CartProductController extends Controller
{
	/**
	 * meta data 
	 *
	 * @return JsonResponse
	 */
	public function addProduct(CartProductRequest $request)
	{
		$quantity = $request->input('quantity');
		$productId = $request->input('productId');
		$userId = $request->input('userId');
		$sizeId = $request->input('sizeId');
		$colorId = $request->input('colorId');


		$cartProductTotalQuantity = CartProduct::where('productId', $productId)->where('userId', $userId)->sum('quantity');
		$cartProducts = CartProduct::where('productId', $productId)->where('userId', $userId)->get();


		$product = Product::find($productId);
		if ($product->availableQuantity < $quantity + $cartProductTotalQuantity) {
			return response()->json(['error' => 'The Quantity is invalid!'])
				->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
		}

		if ($cartProducts->isNotEmpty()) {
			foreach ($cartProducts as $cartProduct) {
				if (($cartProduct && $cartProduct->sizeId === $sizeId && $cartProduct->colorId === $colorId) || ($cartProduct && $cartProduct->sizeId === $sizeId && !isset($cartProduct->colorId)) || ($cartProduct && $cartProduct->colorId === $colorId && !isset($cartProduct->sizeId))) {
					$cartProduct->update([
						'quantity' => $cartProduct->quantity + $quantity
					]);
					$cartProduct->save();
					return response()->json($cartProduct);
				}
			}
			$cartProduct = CartProduct::create($request->all());
			return response()->json($cartProduct);
		} else {
			$cartProduct = CartProduct::create($request->all());
			return response()->json($cartProduct);
		}
	} //meta data()

    public function getCartCount(Request $request): JsonResponse
    {
		$userId = $request->input('userId');
        $totalQuantity = CartProduct::where('userId', $userId)->sum('quantity');
        return response()->json([
            'data' => ['totalQuantity' => $totalQuantity]
        ], Response::HTTP_OK);
    }
	public function prices(Request $request)
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
		$totalPrice = CartProduct::where('userId', $user->id)
			->with('product')
			->get()
			->sum(function ($cartProduct) use($user, $cupon, $cuponProducts, $cuponCategories) {

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
		return response()->json(['data' => array('totalPrice' => $totalPrice)]);
	} //meta data()
}
