<?php

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

Route::group(['middleware'=>['pin.auth','pin.cache']], function(){
    Route::group(['namespace'=>'Dashboard'],function(){
        Route::get('/','DashboardController')->name('home');
        
    });

    Route::group(['namespace'=>'TeamMember'],function(){
        Route::post('/team-member/{member}/restore','TeamMemberController@restore')->name('team-member.restore');
        Route::resource('team-member','TeamMemberController');
        Route::get('export-team-member','ExportsController@export')->name('team-member.export');
        Route::get('import-team-member','ExportsController@import')->name('team-member.import');
        Route::get('index-team-member','ExportsController@index');
    });

    Route::group(['namespace'=>'Artisan'],function(){
        Route::match(['get','post'],'/artisan/{artisan}/restore','ArtisanController@restore')->name('artisan.restore');
        Route::post('artisan/{artisan}/reject','ArtisanController@reject')->name('artisan.reject');
        Route::delete('artisan/{artisan}/ban', 'ArtisanController@banArtisan')->name('artisan.ban-artisan');
        Route::post('artisan/{artisan}/unban', 'ArtisanController@unbanArtisan')->name('artisan.unban-artisan');
        Route::post('artisan/{artisan}/storeApprovalNote','ArtisanController@storeApprovalNote')->name('artisan.storeApprovalNote');
        Route::post('artisan/{artisan}/storeRejectionNote','ArtisanController@storeRejectionNote')->name('artisan.storeRejectionNote');
        Route::post('/artisan/{artisan}/approve','ArtisanController@approve')->name('artisan.approve');
        Route::resource('artisan','ArtisanController');
        Route::get('export-artisan','ExportsController@export')->name('artisan.export');
        Route::get('import-artisan','ImportsController@index')->name('artisan.import.index');
        Route::get("/artisan/{id}/details",'ArtisanController@fetchdetails');
        Route::post('import-artisan','ImportsController@import')->name('artisan.import');
    });

    // Route::group(['namespace'=>'Payment'],function(){
    //     Route::resource('payment','PaymentController');
    //     Route::get('export-payment','ExportsController@export')->name('payment.export');
    // });
    Route::group(['namespace'=>'Category'],function(){
        Route::post('/category/{category}/storeCommission','CategoryController@storeCommission')->name('category.storeCommission');
        Route::resource('category','CategoryController');
        Route::get('export-category','ExportsController@export')->name('category.export');
        Route::get('import-category','ExportsController@import')->name('category.import');
        Route::get("/category/{id}/details",'CategoryController@fetchdetails');
    });

    Route::group(['namespace'=>'Product'],function(){
        Route::post('/product/{product}/managestock','ProductController@updateStock')->name('product.updateStock');
        Route::post('/product/{product}/approve','ProductController@approve')->name('product.approve');
        Route::post('/product/{product}/reject','ProductController@reject')->name('product.reject');
        Route::delete('/product/{product}/ban','ProductController@banProduct')->name('product.ban-product');
        Route::post('/product/{product}/unban','ProductController@unbanProduct')->name('product.unban-product');
        Route::resource('product','ProductController');
        Route::get('export-product','ExportsController@export')->name('product.export');
        Route::get('import-product','ExportsController@import')->name('product.import');
    });
});

Route::group(['namespace'=>'Auth','middleware'=>['pin.auth','pin.cache']],function(){
    Route::get('/change-password','AuthController@changePassword')->name('password.change');
    Route::post('/attempt-change-password','AuthController@attemptChangePassword')->name('password.attempt-change');
    Route::post('/attempt-generate-password/{user}', 'AuthController@generatePassword')->name('attempt-generate-password');
    Route::get('/logout','AuthController@logout')->name('auth.logout');
    Route::get('/profile','AuthController@getProfile')->name('profile.get');
    Route::post('/update-profile','AuthController@saveProfile')->name('profile.store');
});

// without login
Route::group(['namespace'=>'Auth','middleware'=>['pin.guest','pin.cache']],function(){

    Route::get('/login','AuthController@login')->name('auth.login');
    Route::post('/attempt-login','AuthController@attemptLogin')->name('auth.attempt-login');
    Route::post('/forgot-password','AuthController@attemptForgotPassword')->name('password.attempt-forgot');
    Route::get('/password/reminder/{id}/{code}','AuthController@resetPassword')->name('password.reset');
    Route::post('/password/reset/{id}/{code}','AuthController@attemptResetPassword')->name('password.attempt-reset');
});

Route::get('/404',function(){
    return view('errors.404');
 })->name('404');
 
 Route::get('/403',function(){
    return view('errors.403', []);
 })->name('errors.403');
 
 Route::get('/405',function(){
    return view('errors.405');
 })->name('405');
 
 Route::get('/503',function(){
     return view('errors.503');
 })->name('503');
 
 Route::get('unauthorized',function(){
     return view('errors.unauthorized');
 })->name('unauthorized');
 
 Route::get('deactivated',function(){
     return view('errors.deactivated');
 })->name('deactivated');

Route::get('/redirect',function(){
    $intended_route = '/';
    if(session()->has('intended_route')){
        $intended_route = session('intended_route');
        session()->forget('intended_route');
    }
    return redirect($intended_route);
})->name('redirect');
