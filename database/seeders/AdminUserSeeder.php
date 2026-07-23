<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
            $admin = User::updateOrCreate(
                ['email' => 'admin@gmail.cm'],
                [
                    'name' => 'Administrateur Général',
                    'password' => Hash::make('Admin@2026'),
                    'actif' => true,
                ]
            );

        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

    }
}