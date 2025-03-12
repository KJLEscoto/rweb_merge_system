<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schools')->insert([
            [
                'description' => 'STI College Davao',
                'image' => '/resources/img/logos/sti.png',
                'is_featured' => 'on',
            ],
            [
                'description' => 'Ateneo de Davao University',
                'image' => '/resources/img/logos/addu.png',
                'is_featured' => 'on',
            ],
            [
                'description' => 'University of Mindanao',
                'image' => '/resources/img/logos/um.png',
                'is_featured' => 'on',
            ],
            [
                'description' => 'Holy Cross of Davao College',
                'image' => '/resources/img/logos/hcdc.png',
                'is_featured' => 'on',
            ],
            [
                'description' => 'RWeb Solutions, Corp.',
                'image' => '/resources/img/logos/rweb.png',
                'is_featured' => 'on',
            ],
        ]);
    }
}