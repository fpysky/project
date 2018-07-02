<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('adminer')->insert([
            'name' => 'admin',
            'account' => 'admin',
            'password' => encrypt('111111'),
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }
}
