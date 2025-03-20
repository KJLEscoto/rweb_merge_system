<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['position' => 'client'],
            ['position' => 'operations'],
            ['position' => 'content_writer'],
            ['position' => 'graphic_designer'],
            ['position' => 'top_manager'],
            ['position' => 'supervisor'],
            ['position' => 'accounting'],
            ['position' => 'user'],
            ['position' => 'ui_ux'],
            ['position' => 'front_end'],
            ['position' => 'back_end'],
            ['position' => 'sales'],
        ];

        foreach ($roles as &$role) {
            $role['created_at'] = now();
            $role['updated_at'] = now();
        }

        DB::table('roles')->insert($roles);
    }
}
