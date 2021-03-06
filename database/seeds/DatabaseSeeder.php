<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //管理员表
        DB::table('adminer')->insert([
            'name' => 'admin',
            'account' => 'admin',
            'password' => encrypt('111111'),
            'created_at' => time(),
            'updated_at' => time()
        ]);
        //角色表
        DB::table('role')->insert([
            'name' => 'admin',
            'created_at' => time(),
            'updated_at' => time()
        ]);
        //权限表
        DB::table('permission')->insert([[
            'name' => '系统权限设置',
            'route' => '/admin/permission',
            'pid' => 0,
            'icon' => 'certificate',
            'created_at' => time(),
            'updated_at' => time()
        ],[
            'name' => '管理员管理',
            'route' => '/admin/permission/adminlist',
            'pid' => 1,
            'icon' => 'user-o',
            'created_at' => time(),
            'updated_at' => time()
        ],[
            'name' => '角色管理',
            'route' => '/admin/permission/rolelist',
            'pid' => 1,
            'icon' => 'users',
            'created_at' => time(),
            'updated_at' => time()
        ],[
            'name' => '权限管理',
            'route' => '/admin/permission/permissionlist',
            'pid' => 1,
            'icon' => 'drivers-license-o',
            'created_at' => time(),
            'updated_at' => time()
        ]]);
        //管理员角色表
        DB::table('admin_has_role')->insert([
            'adminer_id' => 1,
            'role_id' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ]);
        //角色权限表
        DB::table('role_has_permission')->insert([[
            'permission_id' => 1,
            'role_id' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ],[
            'permission_id' => 2,
            'role_id' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ],[
            'permission_id' => 3,
            'role_id' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ],[
            'permission_id' => 4,
            'role_id' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ]]);
    }
}
