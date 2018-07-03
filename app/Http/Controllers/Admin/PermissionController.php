<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adminer;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\Role;
use Validator;

class PermissionController extends Controller
{
    public function adminList(){
        return view('admin.permission.adminlist');
    }

    /**
     * 获取管理员权限
     * @return array
     */
    public function getAdminPermission(){
        $identity = session('identity');
        return Adminer::getAdminPermission($identity);
    }

    /**
     * 获取管理员列表
     * @param Request $request
     * @return array
     */
    public function getAdminList(Request $request){
        $args = $request->post();
        $args['pSize'] = isset($args['pSize'])?intval($args['pSize']):50;
        $args['name'] = $args['name'] ?? '';
        return Adminer::getAdminList($args);
    }

    /**
     * 获取所有角色
     * @param Request $request
     * @return array
     */
    public function getAllRole(Request $request){
        return Role::getAll();
    }

    /**
     * 管理员提交
     * @param Request $request
     * @return array
     */
    public function adminPost(Request $request){
        $args = $request->post();
        $args['id'] = isset($args['id'])?intval($args['id']):0;
        $args['email'] = $args['email'] ?? '';
        $rules = [
            'name' => 'required',
            'account' => 'required',
            'roles' => 'required'
        ];
        $rulesMsg = [
            'name.required' => '用户名不能为空',
            'account.required' => '账户名不能为空',
            'roles.required' => '角色不能为空'
        ];
        if($args['id'] == 0){
            $rules['password'] = 'required';
            $rulesMsg['password.required'] = '密码不能为空';
        }
        $validator = Validator::make($args,$rules,$rulesMsg);
        if($validator->fails()){
            return ['code' => 1,'message' => $validator->errors()->first()];
        }
        return Adminer::adminPost($args);
    }

    /**
     * 获取管理员角色
     * @param Request $request
     * @return array
     */
    public function getAdminRoles(Request $request){
        $id = $request->post('id',0);
        if(!$id){
            return ['code' =>0,'message' => 'id不能为空'];
        }
        return Adminer::getAdminRoles($id);
    }

    /**
     * 删除管理员
     */
    public function deleteAdmin(Request $request){
        $ids = $request->post('ids','');
        if(empty($ids)){
            return ['code' => 1,'message' => 'ids不能空'];
        }
        foreach($ids as $v){
            if($v == 1){
                return ['code' => 1,'message' => '超级管理员不能删除'];
            }
        }
        return Adminer::deleteAdmin($ids);
    }
}
