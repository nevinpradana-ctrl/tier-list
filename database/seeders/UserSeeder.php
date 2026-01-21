<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing users
        User::truncate();

        // 5 users sesuai dengan user management yang sudah dibuat
        $users = [
            [
                'name' => 'Nevin',
                'email' => 'nevin@localhost',
                'password' => Hash::make('nevin_pass_123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Adit',
                'email' => 'adit@localhost',
                'password' => Hash::make('adit_pass_123'),
                'role' => 'staff_viewer',
            ],
            [
                'name' => 'Radith',
                'email' => 'radith@localhost',
                'password' => Hash::make('radith_pass_123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Nayaka',
                'email' => 'nayaka@localhost',
                'password' => Hash::make('nayaka_pass_123'),
                'role' => 'staff_viewer',
            ],
            [
                'name' => 'Rizqi',
                'email' => 'rizqi@localhost',
                'password' => Hash::make('rizqi_pass_123'),
                'role' => 'staff_editor',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('5 users berhasil di-seed!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('─────────────────────────────────────');
        $this->command->info('Email: nevin@localhost | Password: nevin_pass_123 (Admin)');
        $this->command->info('Email: adit@localhost | Password: adit_pass_123 (Viewer)');
        $this->command->info('Email: radith@localhost | Password: radith_pass_123 (Admin)');
        $this->command->info('Email: nayaka@localhost | Password: nayaka_pass_123 (Viewer)');
        $this->command->info('Email: rizqi@localhost | Password: rizqi_pass_123 (Editor)');
        $this->command->info('─────────────────────────────────────');
    }
}
