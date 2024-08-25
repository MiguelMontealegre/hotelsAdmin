<?php

namespace App\Http\Controllers\Promotion;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Http\Resources\PromotionResource;
use App\Models\Promotions;
use App\Traits\ScopesTrait;
use App\Traits\UploadTrait;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class PromotionController extends Controller
{
    use ScopesTrait, UploadTrait;
    public function createPromotion(PromotionRequest $request)
    {
        $media = $request->input('media');
        $title = $request->input('title');
        $description = $request->input('description');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $discountPercentage = $request->input('discountPercentage');

        $promotion = Promotions::query()->create([
            'title' => $title,
            'description' => $description,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'media' => $media,
            'link' => $request->input('link'),
            'discountPercentage' => $discountPercentage,
        ]);
        return response()->json(PromotionResource::make($promotion))
            ->setStatusCode(ResponseAlias::HTTP_CREATED);
    }
    public function updatePromotion(PromotionRequest $request, $id)
    {
        $promotion = Promotions::findOrFail($id);

        $media = $request->input('media');
        $promotion->title = $request->input('title');
        $promotion->description = $request->input('description');
        $promotion->startDate = $request->input('startDate');
        $promotion->endDate = $request->input('endDate');
        $promotion->media = $media;

        $promotion->link = $request->input('link');
        $promotion->discountPercentage = $request->input('discountPercentage');
        $promotion->save();

        return response()->json(PromotionResource::make($promotion))
            ->setStatusCode(ResponseAlias::HTTP_OK);
    }

    public function getPromotions()
    {
        $currentDate = Carbon::now();

        $promotions = Promotions::whereDate('endDate', '>=', $currentDate)
            ->get();

        return response()->json(['data' => PromotionResource::collection($promotions)])
            ->setStatusCode(ResponseAlias::HTTP_OK);
    }
}
