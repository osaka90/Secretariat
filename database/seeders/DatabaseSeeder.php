<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Add roles if they don't exist
        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        // Remove old admin user if exists
        User::where('email', 'admin@secretariat.test')->orWhere('username', 'Admin')->delete();

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        $superAdmin->assignRole('super_admin');
    }
}
