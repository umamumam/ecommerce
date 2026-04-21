<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'umam@gmail.com'],
            [
                'name' => 'Miftachul Umam',
                'username' => 'umam_admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'is_active' => true,
                'phone' => '085799352991',
                'address' => 'Ds. Ngablak Rt 3/2 Kec. Cluwak, Kab. Pati',
                'province' => 'Jawa Tengah',
                'city' => 'Pati',
                'district' => 'Cluwak',
                'postal_code' => '59157',
                'gender' => 'Laki-laki',
                'dob' => '2001-12-27',
            ]
        );

        // Create Regular User
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'Pembeli Setia',
                'username' => 'buyer1',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'is_active' => true,
                'province' => 'Jawa Tengah',
                'city' => 'Semarang',
                'district' => 'Semarang Tengah',
                'postal_code' => '50131',
            ]
        );
    }
}
