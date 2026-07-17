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
Route::get('admin', $controller_path . '\admin\LoginController@login')->name('login');
Route::post('/admin/login-details', $controller_path . '\admin\LoginController@login_details')->name('admin.login');

//forgot password
Route::get('/admin/forgot_password', $controller_path . '\admin\LoginController@forgot_password')->name('forgot-password');
Route::post('/admin/forgotpass', $controller_path . '\admin\LoginController@sendResetLinkEmail')->name('forgotpass');
Route::get('/admin/reset_password/{token}', $controller_path . '\admin\LoginController@reset_password')->name('reset-password');
Route::post('/admin/resetpass', $controller_path . '\admin\LoginController@reset')->name('resetpass');

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
    Route::get('admin/changepassword', $controller_path . '\admin\LoginController@changepassword');
    Route::post('admin/changepassword',  $controller_path . '\admin\LoginController@storeChangepassword')->name('change.password');

    //logout
    Route::get('logout', function () {
        session()->forget('ADMIN_LOGIN');
        session()->forget('UserDetails');
        session()->flash('error', 'Logout success');
        return redirect('admin');
    })->name('logout');
    
    // Hotels Module
    Route::get('/admin/hotel-categories/status/{id}/{status}', [\App\Http\Controllers\Admin\HotelCategoryController::class, 'changeStatus'])->name('hotel-categories.status');
    Route::resource('/admin/hotel-categories', \App\Http\Controllers\Admin\HotelCategoryController::class);
    Route::get('/admin/hotels/status/{id}/{status}', [\App\Http\Controllers\Admin\HotelController::class, 'changeStatus'])->name('hotels.status');
    Route::resource('/admin/hotels', \App\Http\Controllers\Admin\HotelController::class);
    Route::get('/admin/booking-features/status/{id}/{status}', [\App\Http\Controllers\Admin\BookingFeatureController::class, 'changeStatus'])->name('booking-features.status');
    Route::resource('/admin/booking-features', \App\Http\Controllers\Admin\BookingFeatureController::class);
    Route::get('/admin/hotel-policies/status/{id}/{status}', [\App\Http\Controllers\Admin\HotelPolicyController::class, 'changeStatus'])->name('hotel-policies.status');
    Route::resource('/admin/hotel-policies', \App\Http\Controllers\Admin\HotelPolicyController::class);

    // Restaurants Module
    Route::resource('/admin/restaurant-categories', \App\Http\Controllers\Admin\RestaurantCategoryController::class);
    Route::resource('/admin/restaurants', \App\Http\Controllers\Admin\RestaurantController::class);

    // Attractions Module
    Route::resource('/admin/attraction-categories', \App\Http\Controllers\Admin\AttractionCategoryController::class);
    Route::resource('/admin/attractions', \App\Http\Controllers\Admin\AttractionController::class);

    // Events Module
    Route::resource('/admin/event-categories', \App\Http\Controllers\Admin\EventCategoryController::class);
    Route::resource('/admin/events', \App\Http\Controllers\Admin\EventController::class);

    // Blogs Module
    Route::resource('/admin/blog-categories', \App\Http\Controllers\Admin\BlogCategoryController::class);
    Route::resource('/admin/blogs', \App\Http\Controllers\Admin\BlogController::class);

    // Pages & Settings
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
});

//web
Route::get('/', [HomeController::class, 'index'])->name('web.home');

// Search Routes
Route::get('/search', [\App\Http\Controllers\Frontend\SearchController::class, 'index'])->name('web.search');
Route::get('/search/autocomplete', [\App\Http\Controllers\Frontend\SearchController::class, 'autocomplete'])->name('web.search.autocomplete');

Route::get('/sitemap.xml', [App\Http\Controllers\Frontend\SitemapController::class, 'index'])->name('web.sitemap');
Route::get('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'index'])->name('web.contact');
Route::post('/contact', [App\Http\Controllers\Frontend\ContactController::class, 'submit'])->name('web.contact.submit');
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
Route::get('/blog/{slug}', [\App\Http\Controllers\Frontend\BlogController::class, 'show'])->name('web.blogs.show');

// Pages
Route::get('/{slug}', [\App\Http\Controllers\Frontend\PageController::class, 'show'])->name('web.pages.show');
