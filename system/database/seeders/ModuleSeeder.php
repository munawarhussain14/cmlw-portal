<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('modules')->insert([
            [
                "id"=>1,
                'name' => "Dashboard",
                'slug' => 'dashboard',
            ],
            [
                "id"=>2,
                'name' => "Users",
                'slug' => 'users',
            ],
            [
                "id"=>3,
                'name' => "Roles",
                'slug' => 'roles',
            ],
            [
                "id"=>4,
                'name' => "Permissions",
                'slug' => 'permissions',
            ],
            [
                "id"=>5,
                'name' => "Modules",
                'slug' => 'modules',
            ],
            [
                "id"=>6,
                'name' => "Labours",
                'slug' => 'labours',
            ],
            [
                "id"=>7,
                'name' => "Scholarships",
                'slug' => 'scholarships',
            ],
            [
                "id"=>8,
                'name' => "Skills Development",
                'slug' => 'skills-development',
            ],
            [
                "id"=>9,
                'name' => "Grants",
                'slug' => 'grants',
            ],
            [
                "id"=>10,
                'name' => "Scheme Types",
                'slug' => 'scheme-types',
            ],
            [
                "id"=>11,
                'name' => "Schemes",
                'slug' => 'schemes',
            ]
        ]);
    }
}
