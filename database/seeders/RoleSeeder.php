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

        ];

        DB::table('roles')->insert($roles);
    }
}
