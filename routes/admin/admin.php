<?php

Route::group(['middleware' => 'adminAuth'],function(){
    Route::any('/','IndexController@index');//后台首页
    Route::any('/main','IndexController@main');//后台主页
});

//登录
Route::group(['prefix' => 'public'],function(){
    Route::any('/login','PublicController@login');//登陆
    Route::post('/getCaptcha','PublicController@getCaptcha');//获取验证码
    Route::post('/loginPost','PublicController@loginPost');//登陆验证
    Route::post('/logout','PublicController@logout');//登出
});

//权限管理
Route::group(['prefix' => 'permission','middleware' => 'adminAuth'],function(){
    Route::post('/getAdminPermission','PermissionController@getAdminPermission');//获取管理员权限
});