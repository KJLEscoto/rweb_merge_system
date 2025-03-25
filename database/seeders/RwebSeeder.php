<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RWebSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert into rweb_details table
        $detailId = DB::table('rweb_details')->insertGetId([
            'address' => 'Door 8, FAB Bldg., F.S Dizon Rd., Bacaca, Barangay 19-B (Pob.) Davao City 8000',
            'telephone' => '(082) 286 0004',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert into payment_methods table
        DB::table('payment_methods')->insert([
            [
                'detail_id' => $detailId,
                'method' => 'BPI',
                'account_name' => 'R Web Solutions Corp.',
                'account_number' => '8091-0082-63',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'detail_id' => $detailId,
                'method' => 'GCASH',
                'account_name' => 'Richard Dean Clemente',
                'account_number' => '09176392247',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
