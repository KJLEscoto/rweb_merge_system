<?php

namespace Database\Seeders;

use App\Models\Privilege;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrivilegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $description = [
            'can_create',
            'can_update',
            'can_read',
            'can_delete',
        ];

        foreach ($description as $desc) {
            Privilege::create([
                'description' => $desc,
            ]);
        }
    }
}
