<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Models\Histories;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Fetch the admin role ID
        $adminRole = '2';

        if (!$adminRole) {
            throw new \Exception("Admin role not found! Ensure RoleSeeder is seeded correctly.");
        }

        $authController = app(AuthController::class);

        // Simulate a request with user registration data
        $request = new Request([
            'firstname' => 'admin',
            'name' => 'admin admin admin',
            'lastname' => 'admin',
            'middlename' => 'admin',
            'email' => 'admin@email.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'phone' => '09123456789',
            'gender' => 'female',
            'address' => 'Test Address',
            'school' => null, // Assuming school ID 1
            'student_no' => '1234567890',
            'emergency_contact_fullname' => 'Test Emergency',
            'emergency_contact_number' => '09123456789',
            'emergency_contact_address' => 'Test Emergency Address',
            'role_id' => 1,
        ]);
        

        //@dd($request->all());

        // Call the register method
        $authController->adminRegister($request, app(FileController::class));
    }
}
