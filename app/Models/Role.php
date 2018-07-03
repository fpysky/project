<?php
namespace App\Models;

use App\Http\Resources\RoleResource;
use DB;

class Role extends Base{

    public function roleHasPermission(){
        return $this->hasMany('App\Models\RoleHasPermission','role_id','id');
    }

    /**
     * 得到所有角色
     * @return array
     */
    public static function getAll(){
        $list = RoleResource::collection(Role::all());
        return ['code' => 0,'message' => '','list' =>$list];
    }

    /**
     * 获取角色
     */
    public static function getRoleList($args){
        $where = [];
        if(!empty($args['name'])){
            $name = $args['name'];
            $where[] = ['name','like',"%$name%"];
        }
        $list = Role::where($where)->paginate($args['pSize']);
        $total = $list->total();
        $list = RoleResource::collection($list);
        return ['code' => 0,'message' => '','list' => $list,'total' => $total];
    }

    public static function rolePost($args){
        DB::beginTransaction();
        try{
            if($args['id'] == 0){
                //保存角色
                $insertData = [
                    'name' => $args['name'],
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
                $roleId = Role::insertGetId($insertData);
                //保存角色权限
                if(isset($args['permissions'])){
                    $insertData = [];
                    foreach($args['permissions'] as $k => $v){
                        $insertData[] = [
                            'role_id' => $roleId,
                            'permission_id' => $v,
                            'created_at' => time(),
                            'updated_at' => time(),
                        ];
                    }
                    RoleHasPermission::insert($insertData);
                }else{
                    return ['code' => 1,'message' => '权限不能为空'];
                }
            }else{
                //保存角色
                $role = Role::where('id','=',$args['id'])->firstOrFail();
                $role->name = $args['name'];
                $role->updated_at = time();
                $res = $role->save();
                if(empty($res)){
                    throw new \Exception();
                }
                //保存角色权限
                if(isset($args['permissions'])){
                    RoleHasPermission::where('role_id','=',$args['id'])->delete();
                    $insertData = [];
                    foreach($args['permissions'] as $k => $v){
                        $insertData[] = [
                            'role_id' => $args['id'],
                            'permission_id' => $v,
                            'created_at' => time(),
                            'updated_at' => time(),
                        ];
                    }
                    RoleHasPermission::insert($insertData);
                }else{
                    return ['code' => 1,'message' => '权限不能为空'];
                }
            }
            DB::commit();
            return ['code' => 0,'message' => '操作成功'];
        }catch (\Exception $e){
            DB::rollback();
            return ['code' => 1,'message' => $e->getMessage()];
        }
    }

    /**
     * 获取角色权限
     * @param $id
     * @return array
     */
    public static function getRolePermission($id){
        try{
            $roleHasPermission = Role::find($id)->roleHasPermission;
            $list = [];
            foreaCh($roleHasPermission as $k => $v){
                $list[] = $v['permission_id'];
            }
            return ['code' => 0,'message' => '','list' => $list];
        }catch (\Exception $e){
            return ['code' => 1,'message' => $e->getMessage()];
        }
    }

    /**
     * 删除角色
     * @param $ids
     * @return array
     */
    public static function deleteRole($ids){
        try{
            RoleHasPermission::whereIn('role_id',$ids)->delete();
            Role::whereIn('id',$ids)->delete();
            return ['code' => 0,'message' => '操作成功'];
        }catch (\Exception $e){
            return ['code' => 1,'message' => $e->getMessage()];
        }
    }
}