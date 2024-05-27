<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\ProductController;

Route::prefix('seller')->name('seller.')->group(function(){
    Route::middleware([])->group(function(){
        Route::controller(SellerController::class)->group(function(){
            Route::get('/login','login')->name('login');
            Route::post('/login-handler','loginHandler')->name('login-handler');
            Route::get('/register','register')->name('register');
            Route::post('/create','createSeller')->name('create');
            Route::get('/account/verify/{token}','verifyAccount')->name('verify');
            Route::get('/register-success','registerSuccess')->name('register-success');
        });
    });

    Route::middleware([])->group(function(){

        Route::controller(SellerController::class)->group(function(){
            Route::get('/','home')->name('home');
            Route::post('/logout','logoutHandler')->name('logout');
            Route::get('/profile','profileView')->name('profile');
            Route::post('/change-profile-picture','changeProfilePicture')->name('change-profile-picture');
            Route::get('/shop-settings','shopSettings')->name('shop-settings');
            Route::post('/shop-setup','shopSetup')->name('shop-setup');
            
        });

        //product
        Route::prefix('product')->name('product.')->group(function(){
            Route::controller(ProductController::class)->group(function(){
                  Route::get('/all','allProducts')->name('all-products');
                  Route::get('/add','addProduct')->name('add-product');
                  Route::get('/get-product-category','getProductCategory')->name('get-product-category');
                  Route::post('/create','createProduct')->name('create-product');
                  Route::get('/edit','editProduct')->name('edit-product');
                  Route::post('/update','updateProduct')->name('update-product');
            });
        });
    });
});