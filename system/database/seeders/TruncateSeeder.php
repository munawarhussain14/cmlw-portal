<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class TruncateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_roles')->truncate();
        DB::table('roles_permissions')->truncate();
        DB::table('modules')->truncate();
        DB::table('permissions')->truncate();
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
    }
}
