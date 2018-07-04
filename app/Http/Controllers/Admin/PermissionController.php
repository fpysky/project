<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Adminer;
use App\Models\Permission;
use App\Models\Role;
use Validator;
use App\Http\Requests\AdminFormRequest;

class PermissionController extends Controller
{
    public function adminList(){
        return view('admin.permission.adminlist');
    }

    public function roleList(){
        return view('admin.permission.rolelist');
    }

    public function permissionList(){
        return view('admin.permission.permissionlist');
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
    public function adminPost(AdminFormRequest $request){
        $args = $request->post();
        $args['id'] = isset($args['id'])?intval($args['id']):0;
        $args['email'] = $args['email'] ?? '';
        if($args['id'] == 0 && empty($args['password'])){
            return ['code' => 1,'message' => '密码不能为空'];
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
     * @param Request $request
     * @return array
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

    /**
     * 获取角色列表
     * @param Request $request
     * @return array
     */
    public function getRoleList(Request $request){
        $args = $request->post();
        $args['pSize'] = isset($args['pSize'])?intval($args['pSize']):50;
        $args['name'] = $args['name'] ?? '';
        return Role::getRoleList($args);
    }

    /**
     * 获取到所有权限
     * @return array
     */
    public function getAllPermission(){
        return Permission::getAll();
    }

    /**
     * 角色提交
     * @param Request $request
     * @return array
     */
    public function rolePost(Request $request){
        $args = $request->post();
        $args['id'] = isset($args['id'])?intval($args['id']):0;
        $validator = Validator::make($args,[
            'name' => 'required|max:10|unique:role,name,'.$args['id'],
            'permissions' => 'required'
        ],[
            'name.required' => '角色名不能为空',
            'name.max' => '角色名最大长度为10',
            'name.unique' => '角色名重复',
            'permissions.required' => '角色权限不能为空'
        ]);
        if($validator->fails()){
            return ['code' => 1,'message' => $validator->errors()->first()];
        }
        if($args['id'] == 1){
            return ['code' => 1,'message' => '此角色涉及到系统关键功能无法修改'];
        }
        return Role::rolePost($args);
    }

    /**
     * 获取对应角色权限
     * @param Request $request
     * @return array
     */
    public function getRolePermission(Request $request){
        $id = $request->post('id',0);
        if(!$id){
            return ['code' =>0,'message' => 'id不能为空'];
        }
        return Role::getRolePermission($id);
    }

    /**
     * 删除角色
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function deleteRole(Request $request){
        $ids = $request->post('ids','');
        if(empty($ids)){
            return ['code' => 1,'message' => 'ids不能空'];
        }
        foreach($ids as $v){
            if($v == 1){
                return ['code' => 1,'message' => '此角色不能删除'];
            }
        }
        return Role::deleteRole($ids);
    }

    /**
     * 获取权限列表
     * @param Request $request
     * @return array
     */
    public function getPermissionList(Request $request){
        $args = $request->post();
        $args['pSize'] = isset($args['pSize'])?intval($args['pSize']):50;
        $args['name'] = $args['name'] ?? '';
        return Permission::getPermissionList($args);
    }

    /**
     * 获取权限列表（下拉数据）
     * @return array
     */
    public function getPidOptions(){
        return Permission::getPidOptions();
    }

    /**
     * 权限提交
     * @param Request $request
     * @return array
     */
    public function permissionPost(Request $request){
        $args = $request->post();
        $args['id'] = isset($args['id'])?intval($args['id']):0;
        $validator = Validator::make($args,[
            'name' => 'required',
            'route' => 'required'
        ],[
            'name.required' => '权限名不能为空',
            'route.required' => '路由名称不能为空'
        ]);
        if($validator->fails()){
            return ['code' => 1,'message' => $validator->errors()->first()];
        }
        if($args['id'] == 1 || $args['id'] == 2 || $args['id'] == 3){
            return ['code' => 1,'message' => '此权限涉及到系统关键功能无法修改'];
        }
        return Permission::permissionPost($args);
    }

    /**
     * 删除权限
     */
    public function deletePermission(Request $request){
        $ids = $request->post('ids','');
        if(empty($ids)){
            return ['code' => 1,'message' => 'ids不能空'];
        }
        foreach($ids as $v){
            if($v == 1){
                return ['code' => 1,'message' => '此权限不能删除'];
            }
        }
        return Permission::deletePermission($ids);
    }
}
