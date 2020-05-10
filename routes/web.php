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

Route::get('/', function () {
	echo "aaaaaaaaaaaaaaaaaaaa";
    return view('welcome');
});

//后台 首页 路由
Route::get('admin','Admin\IndexController@index');


//后台 用户 路由
Route::resource('admin/users','Admin\UsersController');

//后台 分类 路由
Route::resource('admin/cates','Admin\CatesController');

//前台首页
Route::resource('home/index','Home\IndexController');