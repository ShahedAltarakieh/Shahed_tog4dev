<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\QuickContributionController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CollectionTeamController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\FactController;
use App\Http\Controllers\InfluencerController;
use App\Http\Controllers\SlidersController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NewsletterSubscriberController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\Admin\NewsAdminController;
use App\Http\Controllers\Admin\NewsCategoryAdminController;
use App\Http\Controllers\Admin\GalleryAdminController;
use App\Http\Controllers\Admin\AnnouncementAdminController;
use App\Http\Controllers\AdminSystemController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect']
    ],
    function () {
        Auth::routes([
            'register' => false, // Registration Routes...
            'reset' => false, // Password Reset Routes...
            'verify' => false, // Email Verification Routes...
        ]);

        Route::prefix('/users')->group(function () {

            Route::get('/', [UsersController::class, 'index'])->middleware('master')->name('users.index');
            Route::get('/user/{id}', [UsersController::class, 'edit'])->name('user.edit')->middleware('master');;
            Route::put('/user/{id}', [UsersController::class, 'update'])->name('user.update')->middleware('master');;
            Route::get('/{id}/payments', [UsersController::class, 'showPayments'])->middleware('master')->name('users.payments');
            Route::get('/data', [UsersController::class, 'fetch_data'])->middleware('master')->name('users.fetch_data');
        });

        // TODO ENHANCE USERS ROUTES
        /* Admins */
        Route::prefix('/admins')->group(function () {
            Route::get('/', [
                AdminController::class,
                'index'
            ])->middleware('master')->name('admin.index');
            Route::get('/create', [
                AdminController::class,
                'create'
            ])->middleware('master')->name('admin.create');
            Route::post('/store', [
                AdminController::class,
                'store'
            ])->middleware('master')->name('admin.store');
            Route::delete('/{id}', [
                AdminController::class,
                'destroy'
            ])->middleware('master')->name('admin.destroy');
            Route::get('/edit/{id}', [
                AdminController::class,
                'edit'
            ])->middleware('master')->name('admin.edit');
            Route::put('/update/{id}', [
                AdminController::class,
                'update'
            ])->middleware('master')->name('admin.update');
        });

        Route::get('newsletter', [NewsletterSubscriberController::class, 'index'])->middleware('master')->name('newsletter.index');
        Route::post('newsletter/{subscriber}', [NewsletterSubscriberController::class, 'updateStatus'])->middleware('master')->name('newsletter.updateStatus');
        Route::delete('newsletter/{subscriber}', [NewsletterSubscriberController::class, 'destroy'])->middleware('master')->name('newsletter.destroy');

        Route::post('ajax/add-to-home', [AjaxController::class, 'add_to_home'])->middleware('master')->name('ajax.add_to_home');

        // Success page (optional)
        Route::get('/payment/success', function () {
            return 'Payment was successful!';
        })->name('payment.success');
        Route::get('sliders/{slider}/view', [SlidersController::class, 'show'])->name('sliders.show')->middleware('master');
        Route::post('sliders/change-status/{id}', [SlidersController::class, 'change_status'])->name('sliders.change_status')->middleware('master');

        Route::group(['prefix' => '{type}', 'where' => ['type' => 'organization|projects|crowdfunding|home']], function () {
            Route::resource('categories', CategoryController::class)->middleware('master');
            Route::get('categories/{category}/view', [CategoryController::class, 'show'])->name('categories.show')->middleware('master');
            Route::get('categories/{category}/seo', [CategoryController::class, 'seo'])->name('categories.seo')->middleware('master');
            Route::post('categories/{category}/seo', [CategoryController::class, 'update_seo'])->name('categories.update_seo')->middleware('master');

            Route::get('items/sorting', [ItemController::class, 'sorting'])->name('items.sorting')->middleware('master');
            Route::post('items/sorting', [ItemController::class, 'storeSorting'])->name('items.storeSorting')->middleware('master');
        });
        Route::get('home/items', [ItemController::class, 'getHomeOnly'])->name('items.itemsHomeOnly')->middleware('master');
        Route::group(['prefix' => '{type}', 'where' => ['type' => 'organization|projects|crowdfunding']], function () {
            Route::get('categories/{category}/view', [CategoryController::class, 'show'])->name('categories.show')->middleware('master');
            Route::get('testimonials/{id}/view', [TestimonialController::class, 'show'])->name('testimonials.show')->middleware('master');
            Route::get('stories/{id}/view', [StoryController::class, 'show'])->name('stories.show')->middleware('master');
            Route::get('partners/{id}/view', [PartnerController::class, 'show'])->name('partners.show')->middleware('master');
            Route::get('facts/{id}/view', [FactController::class, 'show'])->name('facts.show')->middleware('master');
            Route::get('items/{id}/view', [ItemController::class, 'show'])->name('items.show')->middleware('master');

            Route::post('categories/change-status/{id}', [CategoryController::class, 'change_status'])->name('categories.change_status')->middleware('master');
            Route::post('testimonials/change-status/{id}', [TestimonialController::class, 'change_status'])->name('testimonials.change_status')->middleware('master');
            Route::post('stories/change-status/{id}', [StoryController::class, 'change_status'])->name('stories.change_status')->middleware('master');
            Route::post('partners/change-status/{id}', [PartnerController::class, 'change_status'])->name('partners.change_status')->middleware('master');
            Route::post('facts/change-status/{id}', [FactController::class, 'change_status'])->name('facts.change_status')->middleware('master');
            Route::post('items/change-status/{id}', [ItemController::class, 'change_status'])->name('items.change_status')->middleware('master');

            /* Testimonials */
            Route::resource('testimonials', TestimonialController::class)->middleware('master');
            /* Partners */
            Route::resource('partners', PartnerController::class)->middleware('master');
            /* stories */
            Route::resource('stories', StoryController::class)->middleware('master');
            /* facts */
            Route::resource('facts', FactController::class)->middleware('master');
            /* projects */
            Route::resource('items', ItemController::class)->middleware('master');

            Route::get('items/{item}/slider', [ItemController::class, 'slider'])->name('items.slider')->middleware('master');
            Route::get('items/{item}/additional', [ItemController::class, 'additional_info'])->name('items.additional_info')->middleware('master');
            Route::post('items/{item}/additional', [ItemController::class, 'update_additional_info'])->name('items.update_additional_info')->middleware('master');
            Route::get('items/{item}/seo', [ItemController::class, 'seo'])->name('items.seo')->middleware('master');
            Route::post('items/{item}/seo', [ItemController::class, 'update_seo'])->name('items.update_seo')->middleware('master');
            Route::get('items/paid/{item}', [ItemController::class, 'getPaidItems'])->name('items.paid')->middleware('master');
            Route::post('items/upload_slider', [ItemController::class, 'uploadSlider'])->name('items.upload_slider')->middleware('master');
            Route::delete('items/clear-images/{id}', [ItemController::class, 'clearImages'])->name('items.clear_images')->middleware('master');
            Route::delete('items/clear-single-image/{item_id}/{id}', [ItemController::class, 'clearSingleImage'])->name('items.clear_image')->middleware('master');

        })->middleware('master');

        /* Sliders */
        Route::resource('sliders', SlidersController::class)->middleware('master');

        /* influencers */
        Route::resource('influencers', InfluencerController::class)->middleware('master');
        Route::get('/influencers/{id}/payments', [InfluencerController::class, 'showPayments'])->middleware('master')->name('influencer.payments');
        Route::get('/influencers-data', [InfluencerController::class, 'fetch_data'])->middleware('master')->name('influencers.fetch_data');

        /* Collection Team */
        Route::resource('collection_team', CollectionTeamController::class)->middleware('master');
        Route::get('/collection_team/download/excel', [CollectionTeamController::class, 'downloadExcel'])->name('collection_team.download')->middleware('master');

        Route::resource('quick-contributions', QuickContributionController::class)->middleware('master');
        Route::post('quick-contributions/change-status/{id}', [QuickContributionController::class, 'change_status'])->name('quick_contribute.change_status')->middleware('master');

        Route::resource('seo', SeoController::class)->middleware('master');

        Route::post('/cart/confirm/{collectionTeam}', [CollectionTeamController::class, 'confirmPayment'])->name('cart.confirm')->middleware('master');;
        Route::group(['prefix' => '{type}', 'where' => ['type' => 'organization|projects']], function () {
            Route::get('contact-us', [ContactUsController::class, 'index'])->name('contact_us.index')->middleware('master');
            Route::get('contact-us/read', [ContactUsController::class, 'showRead'])->name('contact_us.showRead')->middleware('master');
            Route::get('contact-us/{id}', [ContactUsController::class, 'show'])->name('contact_us.show')->middleware('master');
            Route::post('contact-us/{id}', [ContactUsController::class, 'markAsRead'])->name('contact_us.markAsRead')->middleware('master');
            Route::delete('contact-us/{contact_us}', [ContactUsController::class, 'destroy'])->name('contact_us.destroy')->middleware('master');
        })->middleware('master');

        Route::get('/subscriptions/{active}', [SubscriptionsController::class, 'index'])->name('subscriptions.index')->middleware('master');
        Route::post('/subscriptions/unsubscribe/{id}', [SubscriptionsController::class, 'deactivate']);
        Route::get('/subscriptions/download/excel/{active}', [SubscriptionsController::class, 'downloadCsv'])->name('subscriptions.download')->middleware('master');

        Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index')->middleware('master');
        Route::get('/payments-data', [PaymentsController::class, 'fetch_data'])->name('payments.fetch_data')->middleware('master');
        Route::get('/refunds', [PaymentsController::class, 'index'])->name('refunds.index')->middleware('master');
        Route::get('/payments/{id}', [PaymentsController::class, 'show'])->name('payments.show')->middleware('master');
        Route::get('/payments/download/excel', [PaymentsController::class, 'downloadCsv'])->name('payments.download')->middleware('master');
        Route::post('/payments/refund', [PaymentsController::class, 'refund'])->name('payment.refund')->middleware('master');

        Route::get('/excel', [ExcelController::class, 'index'])->name('excel.index')->middleware('master');
        Route::get('/excel/download-template', [ExcelController::class, 'download_template'])->name('excel.download_template')->middleware('master');
        Route::get('/excel/create', [ExcelController::class, 'create'])->name('excel.create')->middleware('master');
        Route::post('/excel/store', [ExcelController::class, 'store'])->name('excel.store')->middleware('master');
        Route::get('/excel/run-job/{id}', [ExcelController::class, 'runJob'])->name('excel.run-job')->middleware('master');
        Route::get('/excel/mapping', [ExcelController::class, 'mappingData'])->name('excel.map')->middleware('master');
        // Route::post('/excel/mapping', [ExcelController::class, 'storeMappingData'])->name('excel.store_map')->middleware('master');
        Route::get('/excel/{id}', [ExcelController::class, 'show'])->name('excel.show')->middleware('master');
        Route::get('/excel/download/{id}/{type}', [ExcelController::class, 'download_excel'])->name('excel.download')->middleware('master');
        Route::post('excel/mapping', [ExcelController::class, 'updateMap'])->name('excel.update_map');
        Route::post('/excel/delete-map', [ExcelController::class, 'deleteMap'])->name('excel.delete_map');

        Route::prefix('news-management')->middleware('master')->group(function () {
            Route::get('/', [NewsAdminController::class, 'index'])->name('news-admin.index');
            Route::get('/create', [NewsAdminController::class, 'create'])->name('news-admin.create');
            Route::post('/', [NewsAdminController::class, 'store'])->name('news-admin.store');
            Route::get('/{id}', [NewsAdminController::class, 'show'])->name('news-admin.show');
            Route::put('/{id}', [NewsAdminController::class, 'update'])->name('news-admin.update');
            Route::delete('/{id}', [NewsAdminController::class, 'destroy'])->name('news-admin.destroy');
            Route::post('/change-status/{id}', [NewsAdminController::class, 'change_status'])->name('news-admin.change_status');
            Route::post('/duplicate/{id}', [NewsAdminController::class, 'duplicate'])->name('news-admin.duplicate');
        });

        Route::prefix('news-categories')->middleware('master')->group(function () {
            Route::get('/', [NewsCategoryAdminController::class, 'index'])->name('news-categories-admin.index');
            Route::get('/create', [NewsCategoryAdminController::class, 'create'])->name('news-categories-admin.create');
            Route::post('/', [NewsCategoryAdminController::class, 'store'])->name('news-categories-admin.store');
            Route::get('/{id}', [NewsCategoryAdminController::class, 'show'])->name('news-categories-admin.show');
            Route::put('/{id}', [NewsCategoryAdminController::class, 'update'])->name('news-categories-admin.update');
            Route::delete('/{id}', [NewsCategoryAdminController::class, 'destroy'])->name('news-categories-admin.destroy');
            Route::post('/change-status/{id}', [NewsCategoryAdminController::class, 'change_status'])->name('news-categories-admin.change_status');
        });

        Route::prefix('about-management')->middleware('master')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'index'])->name('about-admin.index');
            Route::get('/create', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'create'])->name('about-admin.create');
            Route::post('/', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'store'])->name('about-admin.store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'edit'])->name('about-admin.edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'update'])->name('about-admin.update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'destroy'])->name('about-admin.destroy');
            Route::post('/{id}/publish', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'publish'])->name('about-admin.publish');
            Route::post('/{id}/unpublish', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'unpublish'])->name('about-admin.unpublish');
            Route::post('/{id}/rollback/{versionId}', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'rollback'])->name('about-admin.rollback');
            Route::post('/{pageId}/sections/reorder', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'reorderSections'])->name('about-admin.sections.reorder');
            Route::post('/{pageId}/sections/{sectionId}', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'updateSection'])->name('about-admin.sections.update');
            Route::post('/{pageId}/sections/{sectionId}/toggle', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'toggleVisibility'])->name('about-admin.sections.toggle');
            Route::post('/{pageId}/sections/{sectionId}/items', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'storeItem'])->name('about-admin.items.store');
            Route::post('/{pageId}/sections/{sectionId}/items/reorder', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'reorderItems'])->name('about-admin.items.reorder');
            Route::post('/{pageId}/sections/{sectionId}/items/{itemId}', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'updateItem'])->name('about-admin.items.update');
            Route::delete('/{pageId}/sections/{sectionId}/items/{itemId}', [\App\Http\Controllers\Admin\AboutPageAdminController::class, 'deleteItem'])->name('about-admin.items.delete');
        });

        Route::prefix('nav-settings')->middleware('master')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\NavSettingController::class, 'index'])->name('nav-settings.index');
            Route::put('/', [\App\Http\Controllers\Admin\NavSettingController::class, 'update'])->name('nav-settings.update');
        });

        Route::prefix('announcements')->middleware('master')->group(function () {
            Route::get('/', [AnnouncementAdminController::class, 'index'])->name('announcements.index');
            Route::get('/create', [AnnouncementAdminController::class, 'create'])->name('announcements.create');
            Route::post('/', [AnnouncementAdminController::class, 'store'])->name('announcements.store');
            Route::get('/{id}/edit', [AnnouncementAdminController::class, 'edit'])->name('announcements.edit');
            Route::put('/{id}', [AnnouncementAdminController::class, 'update'])->name('announcements.update');
            Route::delete('/{id}', [AnnouncementAdminController::class, 'destroy'])->name('announcements.destroy');
            Route::post('/change-status/{id}', [AnnouncementAdminController::class, 'changeStatus'])->name('announcements.change_status');
            Route::post('/reorder', [AnnouncementAdminController::class, 'reorder'])->name('announcements.reorder');
        });

        Route::prefix('gallery-management')->middleware('master')->group(function () {
            Route::get('/photos', [GalleryAdminController::class, 'indexPhotos'])->name('gallery-admin.photos.index');
            Route::get('/photos/create', [GalleryAdminController::class, 'createPhoto'])->name('gallery-admin.photos.create');
            Route::post('/photos', [GalleryAdminController::class, 'storePhoto'])->name('gallery-admin.photos.store');
            Route::get('/photos/{id}', [GalleryAdminController::class, 'showPhoto'])->name('gallery-admin.photos.show');
            Route::put('/photos/{id}', [GalleryAdminController::class, 'updatePhoto'])->name('gallery-admin.photos.update');
            Route::delete('/photos/{id}', [GalleryAdminController::class, 'destroyPhoto'])->name('gallery-admin.photos.destroy');
            Route::post('/photos/change-status/{id}', [GalleryAdminController::class, 'changeStatusPhoto'])->name('gallery-admin.photos.change_status');
            Route::post('/photos/duplicate/{id}', [GalleryAdminController::class, 'duplicatePhoto'])->name('gallery-admin.photos.duplicate');

            Route::get('/videos', [GalleryAdminController::class, 'indexVideos'])->name('gallery-admin.videos.index');
            Route::get('/videos/create', [GalleryAdminController::class, 'createVideo'])->name('gallery-admin.videos.create');
            Route::post('/videos', [GalleryAdminController::class, 'storeVideo'])->name('gallery-admin.videos.store');
            Route::get('/videos/{id}', [GalleryAdminController::class, 'showVideo'])->name('gallery-admin.videos.show');
            Route::put('/videos/{id}', [GalleryAdminController::class, 'updateVideo'])->name('gallery-admin.videos.update');
            Route::delete('/videos/{id}', [GalleryAdminController::class, 'destroyVideo'])->name('gallery-admin.videos.destroy');
            Route::post('/videos/change-status/{id}', [GalleryAdminController::class, 'changeStatusVideo'])->name('gallery-admin.videos.change_status');
            Route::post('/videos/duplicate/{id}', [GalleryAdminController::class, 'duplicateVideo'])->name('gallery-admin.videos.duplicate');
        });

        Route::get('/', [
            DashboardController::class,
            'index'
        ])->middleware('master')->name('dashboard');

        Route::prefix('system')->middleware('master')->group(function () {
            Route::get('/activity-logs', [AdminSystemController::class, 'activityLogs'])->name('system.activity-logs');
            Route::get('/notifications', [AdminSystemController::class, 'notifications'])->name('system.notifications');
            Route::get('/settings', [AdminSystemController::class, 'settings'])->name('system.settings');
            Route::get('/health', [AdminSystemController::class, 'systemHealth'])->name('system.health');
            Route::get('/reports', [AdminSystemController::class, 'reportsCenter'])->name('system.reports');
        });

        Route::post('/download-payments-dashboard', [DashboardController::class,'downloadPayments'])->middleware('master')->name('dashboard.download_payments');

        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

        Route::get('common/fix-country', [CommonController::class, 'fix_country'])->middleware('master');
        Route::get('common/fix-phone', [CommonController::class, 'fix_phone'])->middleware('master');
        Route::get('common/excel-cliq', [CommonController::class, 'download_excel_cliq'])->middleware('master');
        Route::get('common/fix-old-payments-name', [CommonController::class, 'fix_old_payments_name'])->middleware('master');
        Route::get('common/store-item-details-from-cart', [CommonController::class, 'storeItemDetailsFromCartToCartDetails'])->middleware('master');
        Route::get('common/send-emails', [CommonController::class, 'send_email'])->middleware('master');
        Route::get('common/store-card-name', [CommonController::class, 'store_card_on_name'])->middleware('master');
        Route::get('common/fetch-network-response', [CommonController::class, 'fetch_network_info'])->middleware('master');

        Route::get('common/download-excel-excel', [CommonController::class, 'download_excel_categories'])->middleware('master');
        Route::get('common/download-excel-items', [CommonController::class, 'download_excel_items'])->middleware('master');
        Route::get('common/download-excel-quick', [CommonController::class, 'download_excel_quicks'])->middleware('master');
        Route::get('common/download-excel-price-list', [CommonController::class, 'download_excel_price_list'])->middleware('master');
        Route::get('common/download-excel-price-options', [CommonController::class, 'download_excel_price_options'])->middleware('master');
        Route::get('common/update-analytic-names', [CommonController::class, 'update_ana_names'])->middleware('master');
        Route::get('common/update-cart-duplicate', [CommonController::class, 'updateCartDuplicate'])->middleware('master');
        Route::get('common/fix-delete-orders', [CommonController::class, 'fix_deleted_orders'])->middleware('master');
        Route::get('common/send-test-unsubscription-template', [CommonController::class, 'send_test_unsubscribtion'])->middleware('master');
        
        Route::prefix('/shortlinks')->group(function () {
            Route::get('/', [
                ShortLinkController::class,
                'index'
            ])->middleware('master')->name('shortlinks.index');
            Route::get('/create', [
                ShortLinkController::class,
                'create'
            ])->middleware('master')->name('shortlinks.create');
            Route::post('/store', [
                ShortLinkController::class,
                'store'
            ])->middleware('master')->name('shortlinks.store');
            Route::delete('/{shortlink}', [
                ShortLinkController::class,
                'destroy'
            ])->middleware('master')->name('shortlinks.destroy');
            Route::get('/edit/{shortlink}', [
                ShortLinkController::class,
                'edit'
            ])->middleware('master')->name('shortlinks.edit');
            Route::put('/update/{shortlink}', [
                ShortLinkController::class,
                'update'
            ])->middleware('master')->name('shortlinks.update');
        });
    }
);

Route::get('refresh-csrf', function(){
    return csrf_token();
});

