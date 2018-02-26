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


// 登录
Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');
Route::post('me', 'AuthController@me');

// 文章
Route::get('/articles', 'ArticleController@index_api');
Route::post('/articles', 'ArticleController@store_api');
Route::post('/articles/update', 'ArticleController@update_api');
Route::get('/articles/publish/{id}', 'ArticleController@publish_api');
Route::get('/articles/delete/{id}', 'ArticleController@destroy_api');
Route::post('/articles/markdown', 'ArticleController@markdown_api');
Route::get('/articles/{id}', 'ArticleController@show_api');
Route::get('/comments', 'CommentController@index_api');
Route::get('/comments/delete/{id}', 'CommentController@destroy_api');
Route::get('/visits', 'VisitController@index_api');
Route::post('/upload', 'UploadController@upload_api');

//