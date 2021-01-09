<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::group(['middleware' => 'auth:api'], function()
{
   Route::post('details', 'UserController@details');

   // Category
   Route::post('addcategory','ADMIN\CategoryController@addcategory')->name('addcategory');
   Route::match(['get','post'],'editcategory/{id}','ADMIN\CategoryController@editcategory')->name('editcategory');
   Route::get('deletecategory/{id}','ADMIN\CategoryController@deletecategory')->name('deletecategory');
   Route::get('allcategory','ADMIN\CategoryController@allcategory')->name('allcategory');

   // Subcategory
   Route::post('addsubcategory','ADMIN\SubacategoryController@addsubcategory')->name('addsubcategory');
   Route::match(['get','post'],'editsubcategory/{id}','ADMIN\SubacategoryController@editsubcategory')->name('editsubcategory');
   Route::get('deletesubcategory/{id}','ADMIN\SubacategoryController@deletesubcategory')->name('deletesubcategory');
   Route::get('allsubcategory','ADMIN\SubacategoryController@allsubcategory')->name('allsubcategory');

   // product
   Route::post('addproduct','ADMIN\ProductController@addproduct')->name('addproduct');
   Route::match(['get','post'],'editproduct/{id}','ADMIN\ProductController@editproduct')->name('editproduct');
   Route::get('allproduct','ADMIN\ProductController@allproduct')->name('allproduct');
   Route::get('deleteproduct/{id}','ADMIN\ProductController@deleteproduct')->name('deleteproduct');
});
