<?php

use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\web\HomeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clearCache', function () {
    Artisan::call('config:cache');
    dd("Config clear..! OK");
});

Route::get('/optimizeClear', function () {
    Artisan::call('optimize:clear');
    dd("Optimize clear..! OK");
});
Route::get('/storageLink', function () {
    $exitCode = Artisan::call('storage:link');
    if ($exitCode === 0) {
        echo "Storage link created successfully.";
    } else {
        echo "Error: Storage link creation failed with exit code $exitCode";
    }
});

$controller_path = 'App\Http\Controllers';

//login
Route::get('admin', $controller_path . '\Admin\LoginController@login')->name('login');
Route::post('/admin/login-details', $controller_path . '\Admin\LoginController@login_details')->name('admin.login');

//forgot password
Route::get('/admin/forgot_password', $controller_path . '\Admin\LoginController@forgot_password')->name('forgot-password');
Route::post('/admin/forgotpass', $controller_path . '\Admin\LoginController@sendResetLinkEmail')->name('forgotpass');
Route::get('/admin/reset_password/{token}', $controller_path . '\Admin\LoginController@reset_password')->name('reset-password');
Route::post('/admin/resetpass', $controller_path . '\Admin\LoginController@reset')->name('resetpass');

//middleware
Route::get('/search-shortcuts/track/{id}', [\App\Http\Controllers\Frontend\SearchController::class, 'trackShortcut'])->name('web.search_shortcuts.track');

Route::group(['middleware' => 'admin_auth'], function () {

    $controller_path = 'App\Http\Controllers';

    // Main Page Route
    Route::get('/dashboard', $controller_path . '\dashboard\DashboardController@index')->name('dashboard.index');
    Route::post('/dashboard/logos/{id?}', $controller_path . '\dashboard\DashboardController@uploadLogos')->name('dashboard.logos.upload');
    Route::delete('/dashboard/logos/delete/{index}', $controller_path . '\dashboard\DashboardController@deleteLogo')->name('dashboard.logos.delete');
    // role
    Route::get('/admin/role', $controller_path . '\user\RoleController@role')->name('role');
    Route::post('/admin/role_list', $controller_path . '\user\RoleController@index_role')->name('role-list');
    Route::get('/admin/create_role', $controller_path . '\user\RoleController@create_role')->name('create-role');
    Route::post('/admin/store_role/{id?}', $controller_path . '\user\RoleController@store_role')->name('store-role');
    Route::get('/admin/edit_role/{id}', $controller_path . '\user\RoleController@edit_role')->name('edit-role');
    Route::get('/admin/delete_role/{id}', $controller_path . '\user\RoleController@delete_role')->name('delete-role');
    Route::get('/admin/permission_rights/{id}', $controller_path . '\user\RoleController@permission_rights')->name('permission-rights');
    Route::post('/admin/permission/{id}', $controller_path . '\user\RoleController@savePermission')->name('permission');

    // user 
    Route::get('/admin/user', $controller_path . '\user\UserController@user')->name('user-add');
    Route::post('/admin/user_list', $controller_path . '\user\UserController@index_user')->name('user-list');
    Route::get('/admin/create_user', $controller_path . '\user\UserController@create_user')->name('create-user');
    Route::post('/admin/store_user/{id?}', $controller_path . '\user\UserController@store_user')->name('store-user');
    Route::get('/admin/edit_user/{id}', $controller_path . '\user\UserController@edit_user')->name('edit-user');
    Route::get('/admin/delete_user/{id}', $controller_path . '\user\UserController@delete_user')->name('delete-user');
    Route::get('/admin/status_user/{id}/{status}', $controller_path . '\user\UserController@status_user')->name('status-user');
    Route::post('/admin/check-emails', $controller_path . '\user\UserController@checkEmail')->name('user.checkemail');

   
    //change password
    Route::get('admin/changepassword', $controller_path . '\Admin\LoginController@changepassword');
    Route::post('admin/changepassword',  $controller_path . '\Admin\LoginController@storeChangepassword')->name('change.password');

    //logout
    Route::get('logout', function () {
        session()->forget('ADMIN_LOGIN');
        session()->forget('UserDetails');
        session()->flash('error', 'Logout success');
        return redirect('admin');
    })->name('logout');
    
    // Hotels Module
    Route::post('/admin/hotel-categories/quick-store', [\App\Http\Controllers\Admin\HotelCategoryController::class, 'quickStore'])->name('hotel-categories.quick-store');
    Route::get('/admin/hotel-categories/status/{id}/{status}', [\App\Http\Controllers\Admin\HotelCategoryController::class, 'changeStatus'])->name('hotel-categories.status');
    Route::resource('/admin/hotel-categories', \App\Http\Controllers\Admin\HotelCategoryController::class);
    Route::get('/admin/hotels/status/{id}/{status}', [\App\Http\Controllers\Admin\HotelController::class, 'changeStatus'])->name('hotels.status');
    Route::resource('/admin/hotels', \App\Http\Controllers\Admin\HotelController::class);
    Route::get('/admin/booking-features/status/{id}/{status}', [\App\Http\Controllers\Admin\BookingFeatureController::class, 'changeStatus'])->name('booking-features.status');
    Route::resource('/admin/booking-features', \App\Http\Controllers\Admin\BookingFeatureController::class);
    Route::get('/admin/hotel-policies/status/{id}/{status}', [\App\Http\Controllers\Admin\HotelPolicyController::class, 'changeStatus'])->name('hotel-policies.status');
    Route::resource('/admin/hotel-policies', \App\Http\Controllers\Admin\HotelPolicyController::class);

    // Restaurants Module
    Route::post('/admin/restaurant-categories/quick-store', [\App\Http\Controllers\Admin\RestaurantCategoryController::class, 'quickStore'])->name('restaurant-categories.quick-store');
    Route::get('/admin/restaurant-categories/status/{id}/{status}', [\App\Http\Controllers\Admin\RestaurantCategoryController::class, 'changeStatus'])->name('restaurant-categories.status');
    Route::resource('/admin/restaurant-categories', \App\Http\Controllers\Admin\RestaurantCategoryController::class);
    Route::get('/admin/restaurants/status/{id}/{status}', [\App\Http\Controllers\Admin\RestaurantController::class, 'changeStatus'])->name('restaurants.status');
    Route::resource('/admin/restaurants', \App\Http\Controllers\Admin\RestaurantController::class);

    // Cuisines Module
    Route::post('/admin/cuisines/quick-store', [\App\Http\Controllers\Admin\CuisineController::class, 'quickStore'])->name('cuisines.quick-store');
    Route::get('/admin/cuisines/status/{id}/{status}', [\App\Http\Controllers\Admin\CuisineController::class, 'changeStatus'])->name('cuisines.status');
    Route::resource('/admin/cuisines', \App\Http\Controllers\Admin\CuisineController::class);

    // Features Module
    Route::post('/admin/features/quick-store', [\App\Http\Controllers\Admin\FeatureController::class, 'quickStore'])->name('features.quick-store');
    Route::get('/admin/features/status/{id}/{status}', [\App\Http\Controllers\Admin\FeatureController::class, 'changeStatus'])->name('features.status');
    Route::resource('/admin/features', \App\Http\Controllers\Admin\FeatureController::class);

    // Attractions Module
    Route::post('/admin/attraction-categories/quick-store', [\App\Http\Controllers\Admin\AttractionCategoryController::class, 'quickStore'])->name('attraction-categories.quick-store');
    Route::get('/admin/attraction-categories/status/{id}/{status}', [\App\Http\Controllers\Admin\AttractionCategoryController::class, 'changeStatus'])->name('attraction-categories.status');
    Route::resource('/admin/attraction-categories', \App\Http\Controllers\Admin\AttractionCategoryController::class);
    Route::get('/admin/attractions/status/{id}/{status}', [\App\Http\Controllers\Admin\AttractionController::class, 'changeStatus'])->name('attractions.status');
    Route::resource('/admin/attractions', \App\Http\Controllers\Admin\AttractionController::class);

    // Events Module
    Route::post('/admin/event-categories/quick-store', [\App\Http\Controllers\Admin\EventCategoryController::class, 'quickStore'])->name('event-categories.quick-store');
    Route::get('/admin/event-categories/status/{id}/{status}', [\App\Http\Controllers\Admin\EventCategoryController::class, 'changeStatus'])->name('event-categories.status');
    Route::resource('/admin/event-categories', \App\Http\Controllers\Admin\EventCategoryController::class);
    Route::get('/admin/events/status/{id}/{status}', [\App\Http\Controllers\Admin\EventController::class, 'changeStatus'])->name('events.status');
    Route::resource('/admin/events', \App\Http\Controllers\Admin\EventController::class);

    // Blogs Module
    Route::resource('/admin/blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class);
    Route::get('/admin/blog-categories/status/{id}/{status}', [\App\Http\Controllers\Admin\BlogCategoryController::class, 'changeStatus'])->name('blog-categories.status');
    Route::resource('/admin/blogs', \App\Http\Controllers\Admin\BlogController::class);
    Route::get('/admin/blogs/status/{id}/{status}', [\App\Http\Controllers\Admin\BlogController::class, 'changeStatus'])->name('blogs.status');

    // Pages & Settings
    Route::post('/admin/contact-messages/status/{id}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'updateStatus'])->name('contact-messages.updateStatus');
    Route::resource('/admin/contact-messages', \App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);
    Route::resource('/admin/pages', \App\Http\Controllers\Admin\PageController::class);
    Route::get('/admin/pages/status/{id}/{status}', [\App\Http\Controllers\Admin\PageController::class, 'changeStatus'])->name('pages.status');
    
    // Amenities
    Route::resource('/admin/amenities', \App\Http\Controllers\Admin\AmenityController::class);
    Route::get('/admin/amenities/status/{id}/{status}', [\App\Http\Controllers\Admin\AmenityController::class, 'changeStatus'])->name('amenities.status');

    Route::resource('/admin/settings', \App\Http\Controllers\Admin\SettingController::class);

    // Search Shortcuts
    Route::post('/admin/search-shortcuts/reorder', [\App\Http\Controllers\Admin\SearchShortcutController::class, 'reorder'])->name('search-shortcuts.reorder');
    Route::get('/admin/search-shortcuts/status/{searchShortcut}/{status}', [\App\Http\Controllers\Admin\SearchShortcutController::class, 'changeStatus'])->name('search-shortcuts.status');
    Route::resource('/admin/search-shortcuts', \App\Http\Controllers\Admin\SearchShortcutController::class);

    // Affiliate Links
    Route::get('/admin/affiliate-links/status/{id}/{status}', [\App\Http\Controllers\Admin\AffiliateLinkController::class, 'changeStatus'])->name('affiliate-links.status');
    Route::get('/admin/affiliate-links/{id}/export/{format}', [\App\Http\Controllers\Admin\AffiliateLinkController::class, 'export'])->name('admin.affiliate-links.export');
    Route::resource('/admin/affiliate-links', \App\Http\Controllers\Admin\AffiliateLinkController::class);

    // Affiliate Promotions
    Route::get('/admin/affiliate-promotions/status/{id}/{status}', [\App\Http\Controllers\Admin\AffiliatePromotionController::class, 'changeStatus'])->name('affiliate-promotions.status');
    Route::resource('/admin/affiliate-promotions', \App\Http\Controllers\Admin\AffiliatePromotionController::class);

    // Newsletter Subscribers
    Route::post('/admin/subscribers/bulk', [\App\Http\Controllers\Admin\SubscriberController::class, 'bulkAction'])->name('subscribers.bulk');
    Route::get('/admin/subscribers/export/{format}', [\App\Http\Controllers\Admin\SubscriberController::class, 'export'])->name('subscribers.export');
    Route::get('/admin/subscribers/status/{id}/{status}', [\App\Http\Controllers\Admin\SubscriberController::class, 'changeStatus'])->name('subscribers.status');
    Route::resource('/admin/subscribers', \App\Http\Controllers\Admin\SubscriberController::class);
});

// Click Tracking Redirect Route
Route::get('/go/{type}/{id}', [\App\Http\Controllers\AffiliateRedirectController::class, 'redirect'])->name('affiliate.redirect');

//web
Route::get('/', [HomeController::class, 'index'])->name('web.home');

// Search Routes
Route::get('/search', [\App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('web.search');
Route::get('/search/autocomplete', [\App\Http\Controllers\Frontend\SearchController::class, 'autocomplete'])->name('web.search.autocomplete');

Route::get('/sitemap.xml', [App\Http\Controllers\Frontend\SitemapController::class, 'index'])->name('web.sitemap');
Route::get('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('web.contact');
Route::middleware('throttle:999,60')->post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('web.contact.submit');
Route::get('/hotels', [App\Http\Controllers\Frontend\HotelController::class, 'index'])->name('web.hotels.index');
Route::get('/hotels/category/{slug}', [App\Http\Controllers\Frontend\HotelController::class, 'category'])->name('web.hotels.category');
Route::get('/hotels/{slug}', [\App\Http\Controllers\Frontend\HotelController::class, 'show'])->name('web.hotels.show');

// Restaurants
Route::get('/restaurants', [\App\Http\Controllers\Frontend\RestaurantController::class, 'index'])->name('web.restaurants.index');
Route::get('/restaurants/category/{slug}', [\App\Http\Controllers\Frontend\RestaurantController::class, 'category'])->name('web.restaurants.category');
Route::get('/restaurants/{slug}', [\App\Http\Controllers\Frontend\RestaurantController::class, 'show'])->name('web.restaurants.show');

// Attractions
Route::get('/attractions', [\App\Http\Controllers\Frontend\AttractionController::class, 'index'])->name('web.attractions.index');
Route::get('/attractions/category/{slug}', [\App\Http\Controllers\Frontend\AttractionController::class, 'category'])->name('web.attractions.category');
Route::get('/attractions/{slug}', [\App\Http\Controllers\Frontend\AttractionController::class, 'show'])->name('web.attractions.show');

// Events
Route::get('/events', [\App\Http\Controllers\Frontend\EventController::class, 'index'])->name('web.events.index');
Route::get('/events/category/{slug}', [\App\Http\Controllers\Frontend\EventController::class, 'category'])->name('web.events.category');
Route::get('/events/{slug}', [\App\Http\Controllers\Frontend\EventController::class, 'show'])->name('web.events.show');

// Blogs
Route::get('/blog', [\App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('web.blogs.index');
Route::get('/blog/sort/{sort}', [\App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('web.blogs.sort');
Route::get('/blog/category/{categorySlug}', [\App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('web.blogs.category');
Route::get('/blog/category/{categorySlug}/sort/{sort}', [\App\Http\Controllers\Frontend\BlogController::class, 'index'])->name('web.blogs.category.sort');
Route::get('/blog/{slug}', [\App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('web.blogs.show');

// Pages
Route::get('/{slug}', [\App\Http\Controllers\Frontend\PageController::class, 'show'])->name('web.pages.show');

// Newsletter
Route::middleware('throttle:5,60')->post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/verify/{token}', [\App\Http\Controllers\NewsletterController::class, 'verify'])->name('newsletter.verify');
Route::get('/newsletter/unsubscribe/{token}', [\App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');

