<?php

use App\Http\Middleware\IsSale;
use App\Http\Middleware\IsAdmin;
use App\Models\ProductSpecification;
use App\Http\Controllers\Tag\TagList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tag\TagCreate;
use App\Http\Controllers\Tag\TagDelete;
use App\Http\Controllers\Tag\TagDetail;
use App\Http\Controllers\Tag\TagUpdate;
use App\Http\Controllers\User\UserList;
use App\Http\Controllers\Cupon\CuponList;
use App\Http\Controllers\Hotel\HotelList;
use App\Http\Controllers\Media\MediaList;
use App\Http\Controllers\Order\OrderList;
use App\Http\Controllers\User\UserDelete;
use App\Http\Controllers\User\UserDetail;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\StylesController;
use App\Http\Controllers\Banner\BannerList;
use App\Http\Controllers\Cupon\CuponDelete;
use App\Http\Controllers\Cupon\CuponDetail;
use App\Http\Controllers\Cupon\CuponUpdate;
use App\Http\Controllers\Hotel\HotelCreate;
use App\Http\Controllers\Hotel\HotelDelete;
use App\Http\Controllers\Hotel\HotelDetail;
use App\Http\Controllers\Hotel\HotelUpdate;
use App\Http\Controllers\Media\MediaDetail;
use App\Http\Controllers\Media\MediaUpdate;
use App\Http\Controllers\Order\OrderCreate;
use App\Http\Controllers\Order\OrderDelete;
use App\Http\Controllers\Order\OrderDetail;
use App\Http\Controllers\Order\OrderUpdate;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Review\ReviewList;
use App\Http\Controllers\Banner\BannerCreate;
use App\Http\Controllers\Banner\BannerDelete;
use App\Http\Controllers\Banner\BannerDetail;
use App\Http\Controllers\Banner\BannerUpdate;
use App\Http\Controllers\Product\ProductList;
use App\Http\Controllers\Review\ReviewCreate;
use App\Http\Controllers\Review\ReviewDelete;
use App\Http\Controllers\Review\ReviewDetail;
use App\Http\Controllers\Review\ReviewUpdate;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Category\CategoryList;
use App\Http\Controllers\Cupon\CuponController;
use App\Http\Controllers\Media\MediaController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Product\ProductCreate;
use App\Http\Controllers\Product\ProductDelete;
use App\Http\Controllers\Product\ProductDetail;
use App\Http\Controllers\Product\ProductUpdate;
use App\Http\Controllers\Category\CategoryCreate;
use App\Http\Controllers\Category\CategoryDelete;
use App\Http\Controllers\Category\CategoryDetail;
use App\Http\Controllers\Category\CategoryUpdate;
use App\Http\Controllers\Passenger\PassengerList;
use App\Http\Controllers\Promotion\PromotionList;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\Wholesale\WholesaleList;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Passenger\PassengerCreate;
use App\Http\Controllers\Passenger\PassengerDelete;
use App\Http\Controllers\Passenger\PassengerDetail;
use App\Http\Controllers\Passenger\PassengerUpdate;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Promotion\PromotionDelete;
use App\Http\Controllers\Promotion\PromotionDetail;
use App\Http\Controllers\Vendor\VendorProductsList;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\CartProduct\CartProductList;
use App\Http\Controllers\MediaEntity\MediaEntityList;
use App\Http\Controllers\Vendor\VendorProductsDelete;
use App\Http\Controllers\Vendor\VendorProductsDetail;
use App\Http\Controllers\Vendor\VendorProductsUpdate;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Sale\SaleAnalyticsController;
use App\Http\Controllers\Auth\PasswordForgotController;
use App\Http\Controllers\CartProduct\CartProductCreate;
use App\Http\Controllers\CartProduct\CartProductDelete;
use App\Http\Controllers\CartProduct\CartProductDetail;
use App\Http\Controllers\CartProduct\CartProductUpdate;
use App\Http\Controllers\MediaEntity\MediaEntityCreate;
use App\Http\Controllers\MediaEntity\MediaEntityDelete;
use App\Http\Controllers\MediaEntity\MediaEntityDetail;
use App\Http\Controllers\MediaEntity\MediaEntityUpdate;
use App\Http\Controllers\OrderProduct\OrderProductList;
use App\Http\Controllers\Promotion\PromotionController;
use App\Http\Controllers\Wholesale\WholesaleController;
use App\Http\Controllers\OrderProduct\OrderProductCreate;
use App\Http\Controllers\OrderProduct\OrderProductDelete;
use App\Http\Controllers\OrderProduct\OrderProductDetail;
use App\Http\Controllers\OrderProduct\OrderProductUpdate;
use App\Http\Controllers\Vendor\VendorProductsController;
use App\Http\Controllers\Auth\PasswordTokenCheckController;
use App\Http\Controllers\CartProduct\CartProductController;
use App\Http\Controllers\MediaEntity\MediaEntityController;
use App\Http\Controllers\Product\ProductComment\ProductCommentList;

/** Open Routes */
Route::prefix('users')
    ->group(function () {
        Route::get('logout', LogoutController::class);
        Route::post('register', [RegisterUserController::class, 'register']);
        Route::post('wholesale-register', [RegisterUserController::class, 'registerWholeSaleUser']);
        Route::post('login', [LoginUserController::class, 'login']);
        Route::post('password-forgot', PasswordForgotController::class);
        Route::post('password-token-check', PasswordTokenCheckController::class);
        Route::post('password-reset', PasswordResetController::class);
    });

Route::group(['middleware' => ['web']], function () {
    Route::get('/external-auth/redirect', [LoginUserController::class, 'externalAuthRedirect']);
    Route::get('/google-auth/callback', [LoginUserController::class, 'externalAuthCallback']);
    Route::get('/facebook-auth/callback', [LoginUserController::class, 'externalAuthCallback']);
    Route::get('/github-auth/callback', [LoginUserController::class, 'externalAuthCallback']);
    Route::get('/twitter-auth/callback', [LoginUserController::class, 'externalAuthCallback']);

	Route::get('/mercadopago-recharge-success', [PaymentController::class, 'mercadopagoRechargeSuccess'])->name('mercadopago-recharge-success');
	Route::get('/paypal-recharge-success', [PaymentController::class, 'paypalRechargeSuccess'])->name('paypal-recharge-success');
	Route::get('/common-cancel-recharge', [PaymentController::class, 'commonCancelRecharge'])->name('common-cancel-recharge');
});

Route::prefix('whosale')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('', [WholesaleList::class, 'list'])->middleware([IsAdmin::class]);
        Route::post('/accept', [WholesaleController::class, 'accept'])->middleware([IsAdmin::class]);
        Route::post('/deny', [WholesaleController::class, 'deny'])->middleware([IsAdmin::class]);
    });

/** No Opened Routes */

/** Mobile Routes with OAuth Token */
Route::middleware(['auth.secure'])->group(function () {
    /** Mobile */
    Route::post('/users/login-by-email', [LoginUserController::class, 'loginByEmail']);
    Route::post('/media/upload-file', [MediaController::class, 'uploadFile']);

    /** Others */
    Route::get('/users/get-by-slug/{hashId}/{userSlug}', [UserController::class, 'getByUserByHashAndSlug']);
    Route::get('/users/get-by-id/{user}', [UserDetail::class, 'show']);
    /** Stories */
});

Route::get('{user}/email-confirmation', [RegisterUserController::class, 'emailConfirmation']);
Route::get('{user}/wholesale-confirmation', [RegisterUserController::class, 'wholeSaleApprove']);
Route::get('{user}/wholesale-denial', [RegisterUserController::class, 'wholeSaleDenail']);
/** User Routes */
Route::prefix('users')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('get-role-list', [UserController::class, 'getRoleList']);
        Route::get('get-by-session', [UserController::class, 'getBySession']);
        Route::get('get-archived', [UserController::class, 'getArchived']);
        Route::get('{user}/send-email-confirmation', [RegisterUserController::class, 'sendEmailConfirmation']);
        Route::get('{user}/email-confirmation', [RegisterUserController::class, 'emailConfirmation'])->middleware([IsAdmin::class]);

        Route::post('create-internal', [RegisterUserController::class, 'createInternal']);
        Route::post('create-admin', [RegisterUserController::class, 'createAdmin'])->middleware([IsAdmin::class]);
        Route::post('login-by-id', [LoginUserController::class, 'loginById'])->middleware([IsAdmin::class]);
        Route::post('get-whosale', [WholesaleController::class, 'getWholesaleUsers'])->middleware([IsAdmin::class]);
        Route::post('update-password', [UserController::class, 'updatePassword']);
        Route::post('update-role', [UserController::class, 'updateRole']);
        Route::post('update-profile-media', [UserController::class, 'updateProfileMedia']);

        Route::get('/', [UserList::class, 'list']);
        Route::get('/{user}', [UserDetail::class, 'show']);
        Route::delete('/{user}', [UserDelete::class, 'delete']);
        Route::put('/archive/{user}', [UserController::class, 'archive']);
        Route::put('/restore/{user}', [UserController::class, 'restore']);
        Route::put('/{user}', [UserController::class, 'update']);
    });

Route::prefix('products')
    ->group(function () {
        Route::get('/product-comments', [ProductCommentList::class, 'list']);
        Route::get('/', [ProductList::class, 'list']);
        Route::get('/{product}', [ProductDetail::class, 'show']);
        Route::get('get-archived', [ProductController::class, 'getArchived']);

        Route::post('/', [ProductController::class, 'create']);
        Route::post('/like/{product}', [ProductController::class, 'like']);
        Route::post('/check-like/{product}', [ProductController::class, 'checkLike']);
        Route::post('/comment/{product}', [ProductController::class, 'comment']);

        Route::put('/{product}', [ProductController::class, 'update']);
		Route::put('basic/{product}', [ProductUpdate::class, 'update']);
        Route::put('/archive/{product}', [ProductController::class, 'archive']);
        Route::put('/restore/{product}', [ProductController::class, 'restore']);

        Route::delete('/{product}', [ProductDelete::class, 'delete']);

        Route::prefix('specifications')
            ->group(function () {
                Route::delete('/{specification}', [ProductSpecification::class, 'delete']);
            });
    });


    Route::prefix('hotels')
    ->group(function () {
        Route::get('/', [HotelList::class, 'list']);
        Route::get('/{hotel}', [HotelDetail::class, 'show']);
        Route::post('/', [HotelCreate::class, 'create']);
        Route::put('/{hotel}', [HotelUpdate::class, 'update']);
        Route::delete('/{hotel}', [HotelDelete::class, 'delete']);
    });

Route::prefix('categories')
    ->group(function () {
        Route::get('/', [CategoryList::class, 'list']);
        Route::get('/{category}', [CategoryDetail::class, 'show']);
        Route::post('/', [CategoryCreate::class, 'create']);
        Route::put('/{category}', [CategoryUpdate::class, 'update']);
        Route::delete('/{category}', [CategoryDelete::class, 'delete']);
    });

Route::prefix('tags')
    ->group(function () {
        Route::get('/', [TagList::class, 'list']);
        Route::get('/{tag}', [TagDetail::class, 'show']);
        Route::post('/', [TagCreate::class, 'create']);
        Route::put('/{tag}', [TagUpdate::class, 'update']);
        Route::delete('/{tag}', [TagDelete::class, 'delete']);
    });


    Route::prefix('passengers')
    ->group(function () {
        Route::get('/', [PassengerList::class, 'list']);
        Route::get('/{passenger}', [PassengerDetail::class, 'show']);
        Route::post('/', [PassengerCreate::class, 'create']);
        Route::put('/{passenger}', [PassengerUpdate::class, 'update']);
        Route::delete('/{passenger}', [PassengerDelete::class, 'delete']);
    });


Route::prefix('reviews')
    ->group(function () {
        Route::get('/', [ReviewList::class, 'list']);
        Route::get('/metadata', [ReviewController::class, 'metaData']);
        Route::get('/{review}', [ReviewDetail::class, 'show']);
        Route::post('/', [ReviewController::class, 'create']);
        Route::put('/{review}', [ReviewUpdate::class, 'update']);
        Route::delete('/{review}', [ReviewDelete::class, 'delete']);
    });


	Route::prefix('payments')
	->group(function () {
		Route::post('/paypal', [PaymentController::class, 'createPaypalRecharge']);
		Route::post('/mercadopago', [PaymentController::class, 'createMercadopagoRecharge']);
		Route::post('/payu', [PaymentController::class, 'createPayuRecharge']);
	});


/** Media Routes */
Route::prefix('media')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/', [MediaList::class, 'list']);
        Route::post('/upload', [MediaController::class, 'uploadFile']);
        Route::post('/upload-file-from-url', [MediaController::class, 'uploadFileFromUrl']);
        Route::post('/encode-image', [MediaController::class, 'encodeImage']);
        Route::post('/create-media-for-aws-url', [MediaController::class, 'createMediaForAWSUrl']);
        Route::delete('/{media}', [MediaController::class, 'delete']);
        Route::get('/{media}', [MediaDetail::class, 'show']);
        Route::put('/{media}', [MediaUpdate::class, 'update']);
        ## User Tags
        Route::post('/add-user-tag', [MediaController::class, 'addUserTag']);
        Route::delete('/delete-user-tag/{mediaUserTag}', [MediaController::class, 'deleteUserTag']);

        //Robin API
        Route::post('/handle-doc', [MediaController::class, 'handleDoc']);
    });

Route::post('upload', [MediaController::class, 'uploadFile']);

/** Media Entity Routes */
Route::prefix('media-entity')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        /** Singleton */
        Route::get('/', [MediaEntityList::class, 'list']);
        Route::post('/', [MediaEntityCreate::class, 'create']);
        Route::get('/{mediaEntity}', [MediaEntityDetail::class, 'show']);
        Route::delete('/{mediaEntity}', [MediaEntityDelete::class, 'delete']);
        Route::put('/{mediaEntity}', [MediaEntityUpdate::class, 'update']);
    });

Route::prefix('media-entities')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('{media}', [MediaEntityController::class, 'getMediaEntitiesByMediaId']);
    });

Route::prefix('helper')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::get('/get-timezone-list', [HelperController::class, 'getTimezoneList']);
        Route::get('/get-timezone-list-with-offset', [HelperController::class, 'getTimezoneWithOffset']);
    });
    Route::prefix('analytics')
        ->middleware(['auth:sanctum',IsSale::class])
        ->group(function () {
            Route::get('/', [SaleAnalyticsController::class, 'getDatas']);
            Route::get('/price', [SaleAnalyticsController::class, 'getPrice']);
            Route::get('/sales-products', [SaleAnalyticsController::class, 'getSalesByProduct']);
            Route::get('/sales', [SaleAnalyticsController::class, 'getProductData']);
        });



	Route::prefix('orders')
	->group(function () {
		Route::get('/', [OrderList::class, 'list']);
		Route::get('/{order}', [OrderDetail::class, 'show']);
		Route::post('/', [OrderCreate::class, 'create']);
		Route::put('/{order}', [OrderController::class, 'update']);
		Route::delete('/{order}', [OrderDelete::class, 'delete']);
	});


	Route::prefix('order-products')
	->group(function () {
		Route::get('/', [OrderProductList::class, 'list']);
		Route::get('/{orderProduct}', [OrderProductDetail::class, 'show']);
		Route::post('/', [OrderProductCreate::class, 'create']);
		Route::put('/{orderProduct}', [OrderProductUpdate::class, 'update']);
		Route::delete('/{orderProduct}', [OrderProductDelete::class, 'delete']);
	});


    Route::prefix('banners')
        ->group(function () {
            Route::get('/', [BannerList::class, 'list']);
            Route::get('/{banner}', [BannerDetail::class, 'show']);
            Route::post('/', [BannerCreate::class, 'create']);
            Route::put('/{banner}', [BannerUpdate::class, 'update']);
            Route::delete('/{banner}', [BannerDelete::class, 'delete']);
        
        });


//---------------------------------------------------


Route::prefix('promotions')
    ->middleware(['auth:sanctum'])
    ->group(function () {
        Route::post('/', [PromotionController::class, 'createPromotion']);
        Route::get('/{promotion}', [PromotionDetail::class, 'show']);
		Route::put('/{id}', [PromotionController::class, 'updatePromotion']);
		Route::get('/',[PromotionList::class, 'list']);
		Route::delete('/{promotion}', [PromotionDelete::class, 'delete']);
    });

Route::prefix('cupon')
    ->group(function () {
        Route::post('/', [CuponController::class, 'create']);
		Route::post('/validate', [CuponController::class, 'validateCupon']);
        Route::get('/', [CuponList::class, 'list']);
        Route::get('/{cupon}', [CuponDetail::class, 'show']);
        Route::put('/{cupon}', [CuponController::class, 'update']);
        Route::delete('/{cupon}', [CuponDelete::class, 'delete']);
    }); 
Route::prefix('styles') 
  ->group(function () {
    Route::post('', [StylesController::class, 'saveStyles']);
    Route::get('', [StylesController::class, 'getStyles']);
}); 
Route::get('get-promotions', [PromotionController::class, 'getPromotions']);

