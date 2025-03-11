<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $description = [
            'dashboard',
            'direct_job_order',
            'operation_job_order',
            'task',
            'revision',
            'approvals',
            'track',
            'users',
            'downloadables',
            'profile',
        ];

        foreach ($description as $desc) {
            Page::create([
                'description' => $desc,
            ]);
        }
    }
}
