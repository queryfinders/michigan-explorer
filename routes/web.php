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
});

//web
Route::get('/', [HomeController::class, 'index'])->name('web.home');
