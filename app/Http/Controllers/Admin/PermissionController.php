<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    /**
     * 获取管理员权限
     * @return array
     */
    public function getAdminPermission(){
        $identity = session('identity');
        //$identity['adminer_id']
        //得到管理员id =》 得到管理员角色 =》 得到角色下的权限
        
    }
}
