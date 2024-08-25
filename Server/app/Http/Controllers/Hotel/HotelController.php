<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hotel;


use App\Traits\ScopesTrait;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;
use App\Models\ProductColor;
use Illuminate\Http\Request;
use App\Models\ProductFeature;
use Illuminate\Support\Carbon;
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
class HotelController extends Controller
{
	use ScopesTrait, UploadTrait;


}//end class
