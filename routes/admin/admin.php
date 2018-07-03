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
    Route::any('/adminlist','PermissionController@adminList');//管理员列表
    Route::post('/getAdminList','PermissionController@getAdminList');//获取管理员列表
    Route::post('/getAllRole','PermissionController@getAllRole');//得到所有的角色
    Route::post('/adminPost','PermissionController@adminPost');//管理员提交
    Route::post('/getAdminRoles','PermissionController@getAdminRoles');//获取管理员角色
    Route::post('/deleteAdmin','PermissionController@deleteAdmin');//删除管理员
    Route::any('/rolelist','PermissionController@roleList');//角色列表
});