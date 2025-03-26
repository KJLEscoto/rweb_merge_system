<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UserController;
use App\Models\Histories;
use App\Models\Page;
use App\Models\Privilege;
use App\Models\RoleChannel;
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
            SchoolSeeder::class,
            RoleSeeder::class,
            PageSeeder::class,
            PrivilegeSeeder::class,
            RWebSeeder::class,
        ]);

        // Fetch the admin role ID
        $adminRole = '2';

        if (!$adminRole) {
            throw new \Exception("Admin role not found! Ensure RoleSeeder is seeded correctly.");
        }

        $authController = app(AuthController::class);

        // Simulate a request with user registration data
        $request = new Request([
            'firstname' => 'Perl Ace Jayme',
            'lastname' => 'Benigno',
            'middlename' => 'Manansala',
            'name' => 'Perl Ace Jayme Benigno Manansala',
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

        $request = new Request([
            'firstname' => 'Perl Ace Jayme',
            'lastname' => 'Benigno',
            'middlename' => 'Manansala',
            'name' => 'Perl Ace Jayme Benigno Manansala',
            'email' => 'ace@email.com',
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
            'role_id' => 6,
        ]);

        // Call the register method
        $authController->adminRegister($request, app(FileController::class));

        $user_id = User::where('email', 'like', '%ace@email.com%')->first()->id;

        //get all the privileges for the admin2
        foreach (Page::get() as $page) {
            foreach (Privilege::get() as $priv) {
                RoleChannel::create([
                    'user_id' => $user_id,
                    'privilege_id' => $priv->id,
                    'page_id' => $page->id,
                ]);
            }
        }
    }
}
