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
	Route::resource('admin/post', 'PostController', ['except' => 'show']);
	//Route::post('admin/post/store', 'PostController@store');
	//Route::post('admin/post/update/{id}', 'PostController@update');
	//Route::post('admin/post/index', 'PostController@index');
	//Route::delete('admin/post/destory', 'PostController@destory');

	Route::resource('admin/tag', 'TagController', ['except' => 'show']);
	Route::get('admin/upload', 'UploadController@index');
	
	Route::post('admin/upload/file', 'UploadController@uploadFile');
	Route::delete('admin/upload/file', 'UploadController@deleteFile');
	Route::post('admin/upload/folder', 'UploadController@createFolder');
	Route::delete('admin/upload/folder', 'UploadController@deleteFolder');
	
});

Route::group(['namespace' => 'Auth'], function(){
	// Loggin in and out
	Route::get('auth/login', 'LoginController@login');
	//Route::get('auth/login', 'Auth\LoginController@postLogin');
	Route::get('auth/logout', 'LoginController@logout');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
