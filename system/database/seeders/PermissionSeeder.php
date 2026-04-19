<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'id' => 1,
                'name' => "Create Dashboard",
                'slug' => 'create-dashboard',
                'module_id' => 1
            ],
            [
                'id' => 2,
                'name' => "Update Dashboard",
                'slug' => 'update-dashboard',
                'module_id' => 1
            ],
            [
                'id' => 3,
                'name' => "Read Dashboard",
                'slug' => 'read-dashboard',
                'module_id' => 1
            ],
            [
                'id' => 4,
                'name' => "Delete Dashboard",
                'slug' => 'delete-dashboard',
                'module_id' => 1
            ],
            [
                'id' => 5,
                'name' => "Create Users",
                'slug' => 'create-users',
                'module_id' => 2
            ],
            [
                'id' => 6,
                'name' => "Update Users",
                'slug' => 'update-users',
                'module_id' => 2
            ],
            [
                'id' => 7,
                'name' => "Read Users",
                'slug' => 'read-users',
                'module_id' => 2
            ],
            [
                'id' => 8,
                'name' => "Delete Users",
                'slug' => 'delete-users',
                'module_id' => 2
            ],
            [
                'id' => 9,
                'name' => "Create Roles",
                'slug' => 'create-roles',
                'module_id' => 3
            ],
            [
                'id' => 10,
                'name' => "Update Roles",
                'slug' => 'update-roles',
                'module_id' => 3
            ],
            [
                'id' => 11,
                'name' => "Read Roles",
                'slug' => 'read-roles',
                'module_id' => 3
            ],
            [
                'id' => 12,
                'name' => "Delete Roles",
                'slug' => 'delete-roles',
                'module_id' => 3
            ],
            [
                'id' => 13,
                'name' => "Create Permissions",
                'slug' => 'create-permissions',
                'module_id' => 4
            ],
            [
                'id' => 14,
                'name' => "Update Permissions",
                'slug' => 'update-permissions',
                'module_id' => 4
            ],
            [
                'id' => 15,
                'name' => "Read Permissions",
                'slug' => 'read-permissions',
                'module_id' => 4
            ],
            [
                'id' => 16,
                'name' => "Delete Permissions",
                'slug' => 'delete-permissions',
                'module_id' => 4
            ],
            [
                'id' => 17,
                'name' => "Create Modules",
                'slug' => 'create-modules',
                'module_id' => 5
            ],
            [
                'id' => 18,
                'name' => "Update Modules",
                'slug' => 'update-modules',
                'module_id' => 5
            ],
            [
                'id' => 19,
                'name' => "Read Modules",
                'slug' => 'read-modules',
                'module_id' => 5
            ],
            [
                'id' => 20,
                'name' => "Delete Modules",
                'slug' => 'delete-modules',
                'module_id' => 5
            ],
            [
                'id' => 21,
                'name' => "Create Labours",
                'slug' => 'create-labours',
                'module_id' => 6
            ],
            [
                'id' => 22,
                'name' => "Update Labours",
                'slug' => 'update-labours',
                'module_id' => 6
            ],
            [
                'id' => 23,
                'name' => "Read Labours",
                'slug' => 'read-labours',
                'module_id' => 6
            ],
            [
                'id' => 24,
                'name' => "Delete Labours",
                'slug' => 'delete-labours',
                'module_id' => 6
            ],
            [
                'id' => 25,
                'name' => "Create Scholarships",
                'slug' => 'create-scholarships',
                'module_id' => 7
            ],
            [
                'id' => 26,
                'name' => "Update Scholarships",
                'slug' => 'update-scholarships',
                'module_id' => 7
            ],
            [
                'id' => 27,
                'name' => "Read Scholarships",
                'slug' => 'read-scholarships',
                'module_id' => 7
            ],
            [
                'id' => 28,
                'name' => "Delete Scholarships",
                'slug' => 'delete-scholarships',
                'module_id' => 7
            ],
            [
                'id' => 29,
                'name' => "Create Skills Devlopment",
                'slug' => 'create-skills-devlopment',
                'module_id' => 8
            ],
            [
                'id' => 30,
                'name' => "Update Skills Devlopment",
                'slug' => 'update-skills-devlopment',
                'module_id' => 8
            ],
            [
                'id' => 31,
                'name' => "Read Skills Devlopment",
                'slug' => 'read-skills-devlopment',
                'module_id' => 8
            ],
            [
                'id' => 32,
                'name' => "Delete Skills Devlopment",
                'slug' => 'delete-skills-devlopment',
                'module_id' => 8
            ],
            [
                'id' => 33,
                'name' => "Create Grants",
                'slug' => 'create-grants',
                'module_id' => 9
            ],
            [
                'id' => 34,
                'name' => "Update Grants",
                'slug' => 'update-grants',
                'module_id' => 9
            ],
            [
                'id' => 35,
                'name' => "Read Grants",
                'slug' => 'read-grants',
                'module_id' => 9
            ],
            [
                'id' => 36,
                'name' => "Delete Grants",
                'slug' => 'delete-grants',
                'module_id' => 9
            ],
            [
                'id' => 37,
                'name' => "Create Scheme Types",
                'slug' => 'create-scheme-types',
                'module_id' => 10
            ],
            [
                'id' => 38,
                'name' => "Update Scheme Types",
                'slug' => 'update-scheme-types',
                'module_id' => 10
            ],
            [
                'id' => 39,
                'name' => "Read Scheme Types",
                'slug' => 'read-scheme-types',
                'module_id' => 10
            ],
            [
                'id' => 40,
                'name' => "Delete Scheme Types",
                'slug' => 'delete-scheme-types',
                'module_id' => 10
            ],
            [
                'id' => 41,
                'name' => "Create Schemes",
                'slug' => 'create-schemes',
                'module_id' => 11
            ],
            [
                'id' => 42,
                'name' => "Update Schemes",
                'slug' => 'update-schemes',
                'module_id' => 11
            ],
            [
                'id' => 43,
                'name' => "Read Schemes",
                'slug' => 'read-schemes',
                'module_id' => 11
            ],
            [
                'id' => 44,
                'name' => "Delete Schemes",
                'slug' => 'delete-schemes',
                'module_id' => 11
            ],
        ]);
    }
}
