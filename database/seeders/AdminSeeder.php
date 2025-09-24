<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if an admin user already exists
        if (Admin::exists()) {
            $this->command->warn('⚠️ Admin already exists, skipping AdminSeeder.');
            return;
        }

        // Create a user record for admin
        $user = User::firstOrCreate(
            ['email' => 'yashnick514@gmail.com'],
            [
                'username'  => 'Yash',
                'firstname' => 'Yashnick',
                'lastname'  => 'Ravindrarajah',
                'password'  => Hash::make('Yash123'), // change this later
                'status'    => 'active',
                'usertype'  => 'admin',
            ]
        );

        // Link that user to the Admin model
        Admin::create([
            'user_id' => $user->id,
        ]);

        $this->command->info('✅ Default admin created: Yashnick514@gmail.com / Yash123');
    }
}
