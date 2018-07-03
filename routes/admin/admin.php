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
    Route::post('/getRoleList','PermissionController@getRoleList');//获取角色列表
    Route::post('/getPermissionList','PermissionController@getPermissionList');//获取权限列表
    Route::post('/getAllPermission','PermissionController@getAllPermission');//得到所有权限
    Route::post('/rolePost','PermissionController@rolePost');//角色提交
    Route::post('/getRolePermission','PermissionController@getRolePermission');//获取角色权限
    Route::post('/deleteRole','PermissionController@deleteRole');//删除角色
    Route::any('/permissionlist','PermissionController@permissionList');//权限列表
    Route::post('/getPermissionList','PermissionController@getPermissionList');//获取权限列表
    Route::post('/getPidOptions','PermissionController@getPidOptions');//获取权限列表（下拉数据）
    Route::post('/permissionPost','PermissionController@permissionPost');//权限提交
    Route::post('/deletePermission','PermissionController@deletePermission');//删除权限
});