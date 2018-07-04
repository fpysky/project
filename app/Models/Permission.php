<?php
namespace App\Models;

use App\Http\Resources\PermissionResource;

class Permission extends Base{

    public function RoleHasPermission(){
        return $this->hasMany('App\Models\RoleHasPermission','permission_id','id');
    }

    /**
     * 获取所有权限
     * @return array
     */
    public static function getAll(){
        $list = Permission::all();
        $list = PermissionResource::collection($list);
        return ['code' => 0,'message' => '','list' => $list];
    }

    /**
     * 获取权限列表
     * @param $args
     * @return array
     */
    public static function getPermissionList($args){
        $where = [];
        if(!empty($args['name'])){
            $name = $args['name'];
            $where[] = ['name','like',"%$name%"];
        }
        $list = Permission::where($where)->paginate($args['pSize']);
        $total = $list->total();
        $list = PermissionResource::collection($list);
        return ['code' => 0,'message' => '','list' =>$list,'total' => $total];
    }

    /**
     * 获取权限列表（下拉框数据）
     * @return array
     */
    public static function getPidOptions(){
        $list = Permission::all();
        $list = PermissionResource::collection($list);
        $ulist = [['label' => '---顶级权限---','value' => 0]];
        foreach($list as $k => $v){
            if($v['pid'] == 0){
                $r['value'] = $v['id'];
                $r['label'] = '---'.$v['name'].'---';
                $ulist[] = $r;
                foreach($list as $ks => $vs){
                    if($vs['pid'] == $v['id']){
                        $rs['value'] = $vs['id'];
                        $rs['label'] = '|___'.$vs['name'].'___';
                        $ulist[] = $rs;
                    }
                }
            }
        }
        return ['code' => 0,'message' => '','list' => $ulist];
    }

    public static function permissionPost($args){
        try{
            if($args['id'] == 0){
                $insertData = [
                    'name' => $args['name'],
                    'route' => $args['route'],
                    'icon' => $args['icon'] ?? '',
                    'pid' => intval($args['pid']),
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
                $permissionId = Permission::insertGetId($insertData);
                //给最高管理员注入权限
                $roleHasPermission = new RoleHasPermission();
                $roleHasPermission->role_id = 1;
                $roleHasPermission->permission_id = $permissionId;
                $roleHasPermission->created_at = time();
                $roleHasPermission->updated_at = time();
                $res = $roleHasPermission->save();
                if(empty($res)){
                    throw new \Exception();
                }
            }else{
                $permission = Permission::where('id','=',$args['id'])->firstOrFail();
                $permission->name = $args['name'];
                $permission->route = $args['route'];
                $permission->icon = $args['icon'];
                $permission->pid = intval($args['pid']);
                $permission->updated_at = time();
                $res = $permission->save();
                if(empty($res)){
                    throw new \Exception();
                }
            }
            return ['code' => 0,'message' => '操作成功'];
        }catch (\Exception $e){
            return ['code' => 1,'message' => $e->getMessage()];
        }
    }

    public static function deletePermission($ids){
        try{
            RoleHasPermission::whereIn('permission_id',$ids)->delete();
            Permission::whereIn('id',$ids)->delete();
            return ['code' => 0,'message' => '操作成功'];
        }catch (\Exception $e){
            return ['code' => 1,'message' => $e->getMessage()];
        }
    }
}