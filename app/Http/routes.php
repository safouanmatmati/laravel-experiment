<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Route::get(
//     '/', function () {
//         return view('welcome');
//     }
// );

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(
    ['middleware' => ['web']], function () {
        Route::auth();
    }
);

/*
|--------------------------------------------------------------------------
| Admin controller
|--------------------------------------------------------------------------
*/
Route::get('/admin', 'AdminController@index')
    ->name('admin.home');
/*
|--------------------------------------------------------------------------
| Admin Product controller
|--------------------------------------------------------------------------
*/
Route::get('/admin/product', 'AdminProductController@index')
    ->name('admin.product');

Route::get('/admin/product/add', 'AdminProductController@create')
    ->name('admin.product.create');
Route::post('/admin/product', 'AdminProductController@store')
    ->name('admin.product.store');

Route::get('/admin/product/{product}', 'AdminProductController@edit')
    ->name('admin.product.edit');
Route::patch('/admin/product/{product}', 'AdminProductController@update')
    ->name('admin.product.update');

Route::delete('/admin/product/{product}', 'AdminProductController@destroy')
    ->name('admin.product.delete');

Route::delete('/admin/product/{product}/{preview}', 'AdminProductController@previewDestroy')
    ->name('admin.product.preview.delete');

/*
|--------------------------------------------------------------------------
| Shop controller
|--------------------------------------------------------------------------
*/
Route::get('/home', 'ShopController@index')
->name('shop.index');
Route::get('/', 'ShopController@index')
  ->name('shop.index');
Route::get('/{target}', 'ShopController@target')
  ->name('shop.target');
Route::get('/{target}/{tag}', 'ShopController@tag')
  ->name('shop.tag');
Route::get('/{target}/{tag}/{product}', 'ShopController@product')
  ->name('shop.product');
