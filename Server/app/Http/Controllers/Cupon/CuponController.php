<?php

namespace App\Http\Controllers\Cupon;

use App\Models\Cupon;
use App\Traits\ScopesTrait;
use App\Traits\UploadTrait;
use App\Models\CuponProduct;
use App\Models\CuponCategory;
use App\Http\Requests\CuponRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CuponResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class CuponController extends Controller
{
    use ScopesTrait, UploadTrait;
    public function create(CuponRequest $request)
    {
        $categories = $request->input('categories');
        $products = $request->input('products');
        $cupon = Cupon::query()->create([
            'code' => $request->input('code'), 
            'discount' => $request->input('discount'),
            'availableQuantity' => $request->input('availableQuantity'),
            'expirationDate' => $request->input('expirationDate'),
        ]);
        foreach($categories as $cat){
			CuponCategory::updateOrCreate(
				[
					'cuponId' => $cupon->id,
					'categoryId' => $cat,
				],
				[
					'cuponId' => $cupon->id,
					'categoryId' => $cat,
				]
			);
		}
        foreach($products as $prod){
            CuponProduct::updateOrCreate(
                [
                    'cuponId' => $cupon->id,
					'productId' => $prod,
                ],
                [
                    'cuponId' => $cupon->id,
                    'productId' => $prod,
                ]
            );
        }  
        return response()->json(CuponResource::make($cupon))
            ->setStatusCode(ResponseAlias::HTTP_CREATED);
    }


    public function update(CuponRequest $request, string $id)
    {
        $categories = $request->input('categories');
        $products = $request->input('products');
        $cupon = Cupon::query()->findOrFail($id);
        $cupon->update([
            'code' => $request->input('code'), 
            'discount' => $request->input('discount'),
            'availableQuantity' => $request->input('availableQuantity'),
            'expirationDate' => $request->input('expirationDate'),
        ]);
     $cuponCategories = CuponCategory::query()->where('cuponId', $cupon->id)->whereNotIn('categoryId', $categories)->get();

        foreach($cuponCategories as $cat){
            $cat->delete();
        }

    $cuponProducts = CuponProduct::query()->where('cuponId', $cupon->id)->whereNotIn('productId', $products)->get();

        foreach($cuponProducts as $prod){
            $prod->delete();
        }
        foreach($categories as $cat){
            CuponCategory::updateOrCreate(
                [
                    'cuponId' => $cupon->id,
					'categoryId' => $cat,
                ],
                [
                    'cuponId' => $cupon->id,
                    'categoryId' => $cat,
                ]
            );
        }
        foreach($products as $prod){
            CuponProduct::updateOrCreate(
                [
                    'cuponId' => $cupon->id,
					'productId' => $prod,
                ],
                [
                    'cuponId' => $cupon->id,
                    'productId' => $prod,
                ]
            );
        }  
        return response()->json(CuponResource::make($cupon))
            ->setStatusCode(ResponseAlias::HTTP_OK);
    }




    public function validateCupon(Request $request)
    {
        $code = $request->input('code');
		$cupon = Cupon::where('code', $code)->first();
		if($cupon){
			return response()->json(CuponResource::make($cupon))
            	->setStatusCode(ResponseAlias::HTTP_OK);
		}
		return response()->json(['error' => 'Invalid cupon'], 422);
    }
	
   
}
