<?php
declare(strict_types=1);

namespace App\Http\Controllers\Review;

use App\Models\Review;
use App\Http\Requests\Review\ReviewRequest;
use App\Models\User;
use App\Http\Resources\Review\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

use App\Mail\SendInfoReview;

class ReviewController extends Controller
{
	/**
     * meta data 
     *
     * @return JsonResponse
     */
    public function metaData(Request $request)
    {
		$stars5 = Review::whereNull('deletedAt')->where('valoration', 5)->count();
		$stars4 = Review::whereNull('deletedAt')->where('valoration', 4)->count();
		$stars3 = Review::whereNull('deletedAt')->where('valoration', 3)->count();
		$stars2 = Review::whereNull('deletedAt')->where('valoration', 2)->count();
		$stars1 = Review::whereNull('deletedAt')->where('valoration', 1)->count();
		return response()->json(['data' => array(
			'stars5' => $stars5,
			'stars4' => $stars4,
			'stars3' => $stars3,
			'stars2' => $stars2,
			'stars1' => $stars1,
		)]);
    }//meta data()

	public function create(ReviewRequest $request){
		$title= $request->input('title');
		$content = $request->input('content');
		$valoration= $request->input('valoration');
		$userId= $request->input('userId');

		$review = review::create([
			'title' => $title,
			'content' => $content,
			'valoration' => $valoration,
			'userId' => $userId,
		]);
		$user = User::find($userId);
		$recipientEmails = User::whereHas('roles', function ($query) {
			$query->where('name', 'ADMIN');
		})->pluck('email')->toArray();

		return response()
		->json(ReviewResource::make($review))
		->setStatusCode(ResponseAlias::HTTP_OK);
	}
}
   