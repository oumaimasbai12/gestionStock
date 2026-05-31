<?php

namespace Database\Seeders;

use App\Models\Chantier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ChantierSeeder::class);

        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleStorekeeper = Role::firstOrCreate(['name' => 'storekeeper']);
        $roleSiteManager = Role::firstOrCreate(['name' => 'site_manager']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@stocket.com'],
            ['name' => 'Stocket Admin', 'password' => bcrypt('12345678')]
        );
        $admin->syncRoles([$roleAdmin]);

        $storekeeper = User::firstOrCreate(
            ['email' => 'storekeeper@stocket.com'],
            ['name' => 'Sébastien Storekeeper', 'password' => bcrypt('12345678')]
        );
        $storekeeper->syncRoles([$roleStorekeeper]);

        $defaultChantier = Chantier::first();

        $manager = User::firstOrCreate(
            ['email' => 'manager@stocket.com'],
            [
                'name' => 'Mohamed Site Manager',
                'password' => bcrypt('12345678'),
                'chantier_id' => $defaultChantier?->id,
            ]
        );
        $manager->syncRoles([$roleSiteManager]);
        if ($defaultChantier && !$manager->chantier_id) {
            $manager->update(['chantier_id' => $defaultChantier->id]);
        }
    }
}
