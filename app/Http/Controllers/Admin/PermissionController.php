<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adminer;
use App\Models\Permission;
use App\Models\RoleHasPermission;

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

        //得到管理员角色
        $adminHasRole = Adminer::find(1)->adminHasRole;
        $ulist = [];
        foreach($adminHasRole as $k => $v){
            //得到角色下的权限
            //--------------20180702
            $res = RoleHasPermission::where('role_id','=',$v['role_id'])->get();
            $ulist[] = $res;
        }
        return ['aaa' => $adminHasRole,'ulist' => $ulist];
    }
}
