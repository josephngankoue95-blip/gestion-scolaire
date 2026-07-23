<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'proviseur',
            'prefecture_etudes',
            'enseignant',
            'surveillant_general',
            'secretaire_intendant',
            'parent',
            'bibliothecaire',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}