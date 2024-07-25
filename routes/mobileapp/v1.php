<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix'    => 'v1',
    'namespace' => 'v1',
    'as'        => 'api.Amounee.'
], function() {
    Route::group(['namespace'=>'Auth'], function(){
        Route::post('/login','AuthController@login')->name('auth.login');
        Route::post('/resend-otp', 'AuthController@resendOtp')->name('auth.resend-otp');
        Route::post('/forgot-password', 'AuthController@attemptForgotPassword')->name('auth.forgot-password');
        Route::post('/reset-password/{id}/{code}', 'AuthController@attemptResetPassword')->name('auth.reset-password');
        Route::post('/register', 'AuthController@registerArtisan')->name('auth.register');

        Route::group(['middleware' => ['jwt.auth', 'pin.api.auth', 'pin.api.checkrole:artisan']], function(){
            Route::post('/verify-otp', 'AuthController@verifyOtp')->name('auth.verify-otp');
            Route::post('/logout','AuthController@logout')->name('auth.logout');
            Route::post('/register-device','AuthController@registerDevice')->name('auth.register-device');
            Route::post('/change-password','AuthController@attemptChangePassword')->name('auth.change-password');

        });
    });

    Route::group(['namespace' => 'Category'], function(){
        Route::resource('category', 'CategoryController')->only(['index']);
    });

     Route::group(['namespace' => 'Artisan', 'middleware' => ['jwt.auth', 'pin.api.auth', 'pin.api.checkrole:artisan']], function(){
        Route::get('/profile/{id}', 'ArtisanController@show')->name('profile.show');
        Route::put('/profile/personal', 'ArtisanController@updatePersonalDetails')->name('profile.update.personal-details');
        Route::put('/profile/address', 'ArtisanController@updateAddressDetails')->name('profile.update.address-details');
        Route::put('/profile/awards', 'ArtisanController@updateAwardDetails')->name('profile.update.award-details');
        Route::put('/profile/image', 'ArtisanController@updateProfileImage')->name('profile.update.profile-image');
        Route::put('/profile/cards', 'ArtisanController@updateArtisanCardIdProof')->name('profile.update.artisan-card-id-proof');

    });
});

