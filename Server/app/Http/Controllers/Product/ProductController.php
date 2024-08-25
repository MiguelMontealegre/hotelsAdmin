<?php

declare(strict_types=1);

namespace App\Http\Controllers\Product;

use App\Models\User;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\Media\Media;
use App\Models\ProductLike;
use App\Models\ProductSize;
use App\Traits\ScopesTrait;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Models\ProductFeature;
use Illuminate\Support\Carbon;
use App\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ProductSpecification;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\Product\ProductResource;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use App\Http\Resources\Product\ProductComment\ProductCommentResource;
use App\Http\Resources\Product\ProductComment\ProductCommentMinResource;
/**
 * Class UserController
 *
 * @extends  Controller
 * @category Controllers
 * @package  App\Http\Controllers

 */
class ProductController extends Controller
{
	use ScopesTrait, UploadTrait;



	public function create(Request $request): JsonResponse
	{
		$categories = $request->input('categories');
		$tags = $request->input('tags');
		$media = $request->input('media');
		$specifications = $request->input('specifications');
		$features = $request->input('features');
		$sizes = $request->input('sizes');
		$colors = $request->input('colors');

		$cleanRequest = $request->except(['categories', 'tags']);
		$product = Product::create($cleanRequest);
		foreach($categories as $cat){
			ProductCategory::updateOrCreate(
				[
					'productId' => $product->id,
					'categoryId' => $cat,
				],
				[
					'productId' => $product->id,
					'categoryId' => $cat,
				]
			);
		}
		foreach($tags as $tag){
			ProductTag::updateOrCreate(
				[
					'productId' => $product->id,
					'tagId' => $tag,
				],
				[
					'productId' => $product->id,
					'tagId' => $tag,
				]
			);
		}
		foreach($specifications as $specification){
			ProductSpecification::create(
				[
					'label' => $specification['label'],
					'value' => $specification['value'],
					'productId' => $product->id,
				]
			);
		}
		foreach($features as $feature){
			ProductFeature::create(
				[
					'value' => $feature['value'],
					'productId' => $product->id,
				]
			);
		}
		foreach($sizes as $size){
			ProductSize::create(
				[
					'value' => $size['value'],
					'productId' => $product->id,
				]
			);
		}
		foreach($colors as $color){
			$colorDb = ProductColor::create(
				[
					'value' => $color['value'],
					'color' => $color['color'],
					'productId' => $product->id,
				]
			);
			$colormedia = $color['media'];
			foreach($colormedia as $cm){
				$mediaElem = Media::find($cm['id']);
				$mediaElem->update([
					'mediableId' => $colorDb->id,
					'mediableType' => get_class($colorDb)
				]);
			}
		}
		foreach($media as $m){
			$mediaElem = Media::find($m);
			$mediaElem->update([
				'mediableId' => $product->id,
				'mediableType' => get_class($product)
			]);
		}
		return response()->json($product);
	}



	public function update(Product $product, Request $request): JsonResponse
	{
		$categories = $request->input('categories');
		$tags = $request->input('tags');
		$media = $request->input('media');
		$specifications = $request->input('specifications');
		$specificationsIds = array();
		foreach ($specifications as $elem) {
			if (isset($elem['id'])) {
				$specificationsIds[] = $elem['id'];
			}
		}
		$features = $request->input('features');
		$featuresIds = array();
		foreach ($features as $elem) {
			if (isset($elem['id'])) {
				$featuresIds[] = $elem['id'];
			}
		}
		$sizes = $request->input('sizes');
		$sizesIds = array();
		foreach ($sizes as $elem) {
			if (isset($elem['id'])) {
				$sizesIds[] = $elem['id'];
			}
		}

		$colors = $request->input('colors');
		$colorsIds = array();
		foreach ($colors as $elem) {
			if (isset($elem['id'])) {
				$colorsIds[] = $elem['id'];
			}
		}


		$cleanRequest = $request->except(['categories', 'tags']);
		$product->update($cleanRequest);

		$productCategories = ProductCategory::query()->where('productId', $product->id)->whereNotIn('id', $categories)->get();
		foreach ($productCategories as $productCategory) {
			$productCategory->delete();
		}
		$productTags = ProductTag::query()->where('productId', $product->id)->whereNotIn('id', $tags)->get();
		foreach ($productTags as $productTag) {
			$productTag->delete();
		}
		$productMedia = Media::query()->where('mediableId', $product->id)->whereNotIn('id', $media)->get();
		foreach ($productMedia as $productMediaElem) {
			$productMediaElem->delete();
		}
		$productSpecifications = ProductSpecification::query()->where('productId', $product->id)->whereNotIn('id', $specificationsIds)->get();
		foreach ($productSpecifications as $productSpecificationElem) {
			$productSpecificationElem->delete();
		}
		$productFeatures = ProductFeature::query()->where('productId', $product->id)->whereNotIn('id', $featuresIds)->get();
		foreach ($productFeatures as $productFeatureElem) {
			$productFeatureElem->delete();
		}
		$productSizes = ProductSize::query()->where('productId', $product->id)->whereNotIn('id', $sizesIds)->get();
		foreach ($productSizes as $productSizeElem) {
			$productSizeElem->delete();
		}
		$productColors = ProductColor::query()->where('productId', $product->id)->whereNotIn('id', $colorsIds)->get();
		foreach ($productColors as $productColorElem) {
			$productColorElem->delete();
		}
		Log::info('colors');
		Log::info($colors);
		foreach ($colors as $colorElem) {
			if(array_key_exists('id', $colorElem)){
				$productColor = ProductColor::find($colorElem['id']);
				$productColor->update([
					'color' => $colorElem['color'],
				]);
				$colorMediaIds = collect($colorElem['media'])->pluck('id')->toArray();
				$colorMediaUpdate = Media::query()->whereIn('id', $colorMediaIds)->get();
				foreach ($colorMediaUpdate as $colorMediaElem) {
					if(!isset($colorMediaElem->mediableId)){
						$colorMediaElem->update([
							'mediableId' => $productColor->id,
							'mediableType' => get_class($productColor)
						]);
					}
				}
				$colorMediaDelete = Media::query()->where('mediableId', $productColor->id)->whereNotIn('id', $colorMediaIds)->get();
				foreach ($colorMediaDelete as $colorMediaElem) {
					$colorMediaElem->delete();
				}
			}
		}

		foreach($categories as $cat){
			ProductCategory::updateOrCreate(
				[
					'productId' => $product->id,
					'categoryId' => $cat,
				],
				[
					'productId' => $product->id,
					'categoryId' => $cat,
				]
			);
		}
		foreach($tags as $tag){
			ProductTag::updateOrCreate(
				[
					'productId' => $product->id,
					'tagId' => $tag,
				],
				[
					'productId' => $product->id,
					'tagId' => $tag,
				]
			);
		}
		foreach($media as $m){
			$mediaElem = Media::find($m);
			$mediaElem->update([
				'mediableId' => $product->id,
				'mediableType' => get_class($product)
			]);
		}
		foreach($specifications as $s){
			if(!array_key_exists('id', $s)){
				ProductSpecification::create(
					[
						'label' => $s['label'],
						'value' => $s['value'],
						'productId' => $product->id,
					]
				);
			}
		}
		foreach($features as $f){
			if(!array_key_exists('id', $f)){
				ProductFeature::create(
					[
						'value' => $f['value'],
						'productId' => $product->id,
					]
				);
			}
		}
		foreach($sizes as $s){
			if(!array_key_exists('id', $s)){
				ProductSize::create(
					[
						'value' => $s['value'],
						'productId' => $product->id,
					]
				);
			}
		}
		foreach($colors as $c){
			if(!array_key_exists('id', $c)){
				$colorDb = ProductColor::create(
					[
						'value' => $c['value'],
						'color' => $c['color'],
						'productId' => $product->id,
					]
				);
				$colormedia = $c['media'];
				foreach($colormedia as $cm){
					$mediaElem = Media::find($cm['id']);
					$mediaElem->update([
						'mediableId' => $colorDb->id,
						'mediableType' => get_class($colorDb)
					]);
				}
			}
		}
		return response()->json($product);
	}



	public function like(Product $product, Request $request)
	{
		if(auth('sanctum')->user()){
			$user = auth('sanctum')->user();
			$checkExisting = ProductLike::where('productId', $product->id)
				->where('userId', $user->id)
				->first();
			if ($checkExisting){
				$user->likes()->detach($product);  
				return response()
					->json(['status' => 'detached'])
					->setStatusCode(ResponseAlias::HTTP_OK);
			} else {
				$user->likes()->attach($product, ['id'=> Str::uuid(36)]);  
				return response()
					->json(['status' => 'attached'])
					->setStatusCode(ResponseAlias::HTTP_OK);
			}
		} else {
			return response()->json(['error' => 'Something went wrong'], 500);
		}
	}



	public function comment(Product $product, Request $request)
	{
		if(auth('sanctum')->user()){
			$user = auth('sanctum')->user();
			$content = $request->input('content');
			$commentId = $request->input('commentId');
			$replyId = $request->input('replyId');
			$comment = $user->comments()->create([
				'id'=> Str::uuid(36), 
				'productId' => $product->id,
				'content' => $content,
				'commentId' => $commentId,
				'replyId' => $replyId,
			]);
			return response()->json($comment->replyId ? ProductCommentMinResource::make($comment) : ProductCommentResource::make($comment));
		} else {
			return response()->json(['error' => 'Something went wrong'], 500);
		}
	}



	public function checkLike(Product $product, Request $request)
	{
		if(auth('sanctum')->user()){
			$user = auth('sanctum')->user();
			$checkExisting = ProductLike::where('productId', $product->id)
				->where('userId', $user->id)
				->first();
			if ($checkExisting){
				return response()
					->json(['status' => 'attached'])
					->setStatusCode(ResponseAlias::HTTP_OK);
			} else {
				return response()
					->json(['status' => 'detached'])
					->setStatusCode(ResponseAlias::HTTP_OK);
			}
		} else {
			return response()->json(['error' => 'Something went wrong'], 500);
		}
	}




	/**
	 * Get archived
	 *
	 * @param  PaginationRequest $request
	 * @return JsonResponse
	 */
	public function getArchived(PaginationRequest $request): JsonResponse
	{
		$builder = User::query()->whereNotNull('archivedAt')
			->select(['products.*']);

		if ($request->has('q')) {
			$q = $request->get('q');
			$builder->where(
				function ($query) use ($q) {
					$query->whereRaw('products.title LIKE ?', "%{$q}%")
						->orWhereRaw('products.description LIKE ?', "%{$q}%")
						->orWhereRaw('products.price LIKE ?', "%{$q}%");
				}
			);
		}

		return response()
			->json(ProductResource::collection($builder->get()))
			->setStatusCode(ResponseAlias::HTTP_OK);
	} //end getArchived()


	/**
	 * Archived Product
	 *
	 * @param  Product $product
	 * @return JsonResponse
	 */
	public function archive(Product $product): JsonResponse
	{
		$product->archivedAt = \Carbon\Carbon::now();
		$product->save();

		return response()
			->json(['message' => 'Product Archived Updated'])
			->setStatusCode(ResponseAlias::HTTP_OK);
	} //end archive()


	/**
	 * Restore Product
	 *
	 * @param  string $productId
	 * @return JsonResponse
	 */
	public function restore(string $productId): JsonResponse
	{
		$product  = Product::withTrashed()->find($productId);
		Product::query()->where('id', $productId)
			->update(['archivedAt' => null]);
		$product->restore();
		return response()
			->json(['message' => 'Product restored successful'])
			->setStatusCode(ResponseAlias::HTTP_OK);
	} //end restore()

}//end class
