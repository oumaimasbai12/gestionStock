<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleStorekeeper = Role::create(['name' => 'storekeeper']);
        $roleSiteManager = Role::create(['name' => 'site_manager']);

        $admin = User::create([
            'name' => 'Stocket Admin',
            'email' => 'admin@stocket.com',
            'password' => bcrypt('12345678'),
        ]);
        $admin->assignRole($roleAdmin);

        $storekeeper = User::create([
            'name' => 'Sébastien Storekeeper',
            'email' => 'storekeeper@stocket.com',
            'password' => bcrypt('12345678'),
        ]);
        $storekeeper->assignRole($roleStorekeeper);

        $manager = User::create([
            'name' => 'Mohamed Site Manager',
            'email' => 'manager@stocket.com',
            'password' => bcrypt('12345678'),
        ]);
        $manager->assignRole($roleSiteManager);
    }
}
