<?php
namespace App\Models;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\AdminerResource;
use DB;

class Adminer extends Base{
    public function adminHasRole(){
        return $this->hasMany('App\Models\AdminHasRole','adminer_id','id');
    }

    /**
     * 获取管理员权限
     * @return array
     */
    public static function getAdminPermission($identity){
        //得到管理员角色
        $adminHasRole = Adminer::find($identity['adminer_id'])->adminHasRole;
        $ids = [];
        foreach($adminHasRole as $k => $v){
            //得到角色下的权限
            $roleHasPermission = RoleHasPermission::where('role_id','=',$v['role_id'])->get();
            foreach($roleHasPermission as $k => $v){
                $ids[] = $v['permission_id'];
            }
        }
        $list = Permission::whereIn('id',$ids)->get();
        $list = PermissionResource::collection($list);
        $ulist = [];
        foreach($list as $ks => $vs){
            if($vs['pid'] == 0){
                $r['id'] = $vs['id'];
                $r['pid'] = $vs['pid'];
                $r['route'] = $vs['route'];
                $r['name'] = $vs['name'];
                $ulist[] = $r;
                unset($r);
            }
        }

        //再找出子节点
        foreach($list as $ks => $vs){
            if($vs['pid'] != 0){
                foreach($ulist as $kss => $vss){
                    if($vss['id'] == $vs['pid']){
                        $r['id'] = $vs['id'];
                        $r['pid'] = $vs['pid'];
                        $r['route'] = $vs['route'];
                        $r['name'] = $vs['name'];
                        $ulist[$kss]['_child'][] = $r;
                        unset($r);
                    }
                }
            }
        }
        return ['code' => 0,'message' => '','list' => $ulist];
    }

    /**
     * 获取管理员列表
     * @param $args
     * @return array
     */
    public static function getAdminList($args){
        $where = [];
        if(!empty($args['name'])){
            $name = $args['name'];
            $where[] = ['name','like',"%$name%"];
        }
        $list = Adminer::where($where)->paginate($args['pSize']);
        $total = $list->total();
        $list = AdminerResource::collection($list);
        return ['code' => 0,'message' => '','list' =>$list,'total' => $total];
    }

    /**
     * 管理员提交
     * @param $args
     * @return array
     */
    public static function adminPost($args){
        DB::beginTransaction();
        try{
            if($args['id'] == 0){
                //保存管理员
                $insertData = [
                    'name' => $args['name'],
                    'account' => $args['account'],
                    'email' => $args['email'] ?? '',
                    'password' => encrypt($args['password']),
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
                $adminerId = Adminer::insertGetId($insertData);
                //指派角色
                if (isset($args['roles'])) {
                    $insertData = [];
                    foreach($args['roles'] as $k => $v){
                        $insertData[] = [
                            'adminer_id' => $adminerId,
                            'role_id' => $v
                        ];
                    }
                    AdminHasRole::insert($insertData);
                }else{
                    return ['code' => 1,'message' => '角色不能为空'];
                }
            }else{
                //保存管理员
                $adminer = Adminer::findOrFail($args['id']);
                $adminer->name = $args['name'];
                $adminer->email = $args['email'];
                $adminer->account = $args['account'];
                $res = $adminer->save();
                if(empty($res)){
                    throw new \Exception();
                }
                //指派角色
                if (isset($args['roles'])) {
                    AdminHasRole::where('adminer_id','=',$args['id'])->delete();
                    $insertData = [];
                    foreach($args['roles'] as $k => $v){
                        $insertData[] = [
                            'adminer_id' => $args['id'],
                            'role_id' => $v
                        ];
                    }
                    AdminHasRole::insert($insertData);
                }else{
                    return ['code' => 1,'message' => '角色不能为空'];
                }
            }
            DB::commit();
            return ['code' => 0,'message' => '操作成功'];
        }catch(\Exception $e){
            DB::rollback();
            return ['code' => 1,'message' => $e->getMessage()];
        }
    }

    /**
     * 得到管理员角色
     * @param $id
     * @return array
     */
    public static function getAdminRoles($id){
        $adminer = Adminer::where('id','=',$id)->firstOrfail();
        $adminRoles = $adminer->adminHasRole;
        $list = [];
        foreach($adminRoles as $k => $v){
            $list[] = $v['role_id'];
        }
        return ['code' => 0,'message' => '','list' => $list];
    }

    /**
     * 删除管理员
     * @param $ids
     * @return array
     */
    public static function deleteAdmin($ids){
        try{
            AdminHasRole::whereIn('adminer_id',$ids)->delete();
            Adminer::whereIn('id',$ids)->delete();
            return ['code' => 0,'message' => '操作成功'];
        }catch (\Exception $e){
            return ['code' => 1,'message' => $e->getMessage()];
        }
    }
}