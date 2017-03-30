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

//Route::get('/', function () {
//     return view('welcome');
//});

// Blog Pages
Route::get('/', function(){
     return redirect('login');
});

Route::get('blog', 'BlogController@index');

Route::get('blog/{slug}', 'BlogController@showPost');

// Admin area

Route::get('admin', function(){
	return redirect('/admin/post');
});

Route::group(['namespace' => 'Admin', 'middleware' => 'auth'], function (){
	Route::resource('admin/post', 'PostController');
	Route::resource('admin/tag', 'TagController');
	Route::get('admin/upload', 'UploadController@index');
});

// Loggin in and out
Route::get('auth/login', 'Auth\LoginController@login');
//Route::get('auth/login', 'Auth\LoginController@postLogin');
Route::get('auth/logout', 'Auth\LoginController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index');
