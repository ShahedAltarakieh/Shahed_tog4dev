<?php


use App\Http\Controllers\Api\V1\CommonController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\SliderController;
//use App\Http\Controllers\Api\V1\CommonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\PartnerController;
use App\Http\Controllers\Api\V1\TestimonialController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\CollectionTeamController;
use App\Http\Controllers\Api\V1\ContactUsController;
use App\Http\Controllers\Api\V1\FactController;
use App\Http\Controllers\Api\V1\NewsletterSubscriberController;
use App\Http\Controllers\Api\V1\StoryController;
use App\Http\Controllers\Api\V1\EfawateercomController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\QuickContributionController;
use App\Http\Controllers\Api\V1\ReferralController;
use App\Http\Controllers\Api\V1\ShortLinkController;

Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/login', [AuthController::class, 'login'])->name('login'); // Apply rate limiting for login attempts
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
    Route::get('/shortlinks/{code}', [ShortLinkController::class, 'index']);

    Route::get('/get-currency/{code}', [UserController::class, 'get_currency']);

    // facts
    Route::get('/facts', [FactController::class, 'index'])->name('facts');
    // Stories
    Route::get('/stories', [StoryController::class, 'index'])->name('stories');
    // Partners
    Route::get('/partners', [PartnerController::class, 'index'])->name('partners');
    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    // Testimonials
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials');
    // Items
    Route::get('/items', [ItemController::class, 'index'])->name('items');
    Route::get('/items/{slug}', [ItemController::class, 'show'])->name('item');

    // NewsLetter
    Route::post('/newsletter', [NewsletterSubscriberController::class, 'store'])->name('newsletter');
    // Contact Us
    Route::post('/contact-us', [ContactUsController::class, 'store'])->name('contact_us');
    // Sliders
    Route::get('/sliders', [SliderController::class, 'index'])->name('sliders');

    Route::get('/quick-contributions', [QuickContributionController::class, 'index']);

    Route::get('/payment-redirect/{id}', [PaymentController::class, 'paymentRedirect']);

    Route::get('/payment/receiveWebhook', [PaymentController::class, 'paymentRedirect']);

    Route::middleware('efawateercom.ip')->group(function () {
        Route::get('/efawateercom/inquiry', [EfawateercomController::class, 'inquiry'])->name('efawateercom.inquiry');
        Route::get('/efawateercom/receive-payment', [EfawateercomController::class, 'receivePayment'])->name('efawateercom.receive_payment');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/change-password', [AuthController::class, 'changePassword']);

        Route::put('/user/profile', [UserController::class, 'updateProfile']);

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        // User routes
        Route::get('/user', [UserController::class, 'user'])->middleware('throttle:api')->name('user.profile');

        Route::get('/payment/getPayments', [PaymentController::class, 'getAllPayments']);
        Route::put('/subscriptions/unsubscribe/{id}', [PaymentController::class, 'deactivate']);
        Route::get('/payment/history/subscriptions', [PaymentController::class, 'getPaymentHistorySubscriptions'])->name('payment.history_subscription');
    });

    Route::get('/payments/status/{cart_id}', [PaymentController::class, 'getPaymentStatusDetails']);
    Route::get('/payments/store-meta-purchase/{cart_id}', [PaymentController::class, 'updateMetaPurchase']);

    Route::post('/payment/create-user', [PaymentController::class, 'create_guest']);
    Route::post('/payment/create', [PaymentController::class, 'handlePayment']);
    Route::post('/payment/orange-money', [PaymentController::class, 'initiatePhoneOM']);
    Route::post('/payment/orange-money/pay', [PaymentController::class, 'payOrangeMoney']);

    Route::get('/cart', [CartController::class, 'getCartItems']);
    Route::post('/cart/add', [CartController::class, 'store']);
    Route::post('/cart/store-dedication-names', [CartController::class, 'storeDedicationNames']);
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']);

    Route::post('collection-team', [CollectionTeamController::class, 'store']);


   // Route::get('/payment/callback', [PaymentController::class, 'handleCallbackGet'])->name('payment.callback_get');
    Route::post('/payment/callback', [PaymentController::class, 'handleCallback'])->name('payment.callback');
    Route::get('/payment/renew', [PaymentController::class, 'renew']);
    Route::get('/referral/store', [ReferralController::class, 'store']);

//    ReferralController
    // Fallback for undefined routes
    // Fallback route
    // Route::fallback(function () {
    //     return response()->json(['message' => 'Route not found'], 404);
    // });
});
