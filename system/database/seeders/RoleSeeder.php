<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => "Super Admin",
                'slug' => 'super-admin',
            ],
            [
                'name' => "Admin",
                'slug' => 'admin',
            ]
        ]);
    }
}
