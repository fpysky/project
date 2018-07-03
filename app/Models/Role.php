<?php
namespace App\Models;

use App\Http\Resources\RoleResource;

class Role extends Base{
    /**
     * 得到所有角色
     * @return array
     */
    public static function getAll(){
        $list = RoleResource::collection(Role::all());
        return ['code' => 0,'message' => '','list' =>$list];
    }
}