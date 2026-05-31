<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $roleUser = Role::create(['name' => 'user']);
        $roleAdmin = Role::create(['name' => 'admin']);

        $permissions = [
            'create users',
            'read users',
            'update users',
            'delete users'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $admin = new User();
        $admin->name = 'Stocket Admin';
        $admin->email = 'admin@stocket.com';
        $admin->password = bcrypt('12345678');
        $admin->assignRole($roleAdmin);
        $roleAdmin->givePermissionTo($permissions);
        $admin->save();

        $user = new User();
        $user->name = 'Test User';
        $user->email = 'test@gmail.com';
        $user->password = bcrypt('12345678');
        $user->assignRole($roleUser);
        $user->save();

        User::factory(50)->create()->each(function ($user) use ($roleUser) {
            $user->assignRole($roleUser);
        });

        Customer::factory(50)->create();

        // Crear proveedores manualmente
        $suppliers = [
            ['nit' => '123456789', 'name' => 'Tech Solutions', 'phone' => '3001234567', 'email' => 'contact@techsolutions.com', 'address' => 'Calle 123, Ciudad A'],
            ['nit' => '987654321', 'name' => 'CompuParts', 'phone' => '3109876543', 'email' => 'ventas@compuparts.com', 'address' => 'Avenida 456, Ciudad B'],
            ['nit' => '456789123', 'name' => 'GamerZone Supplies', 'phone' => '3204567891', 'email' => 'soporte@gamerzone.com', 'address' => 'Carrera 789, Ciudad C'],
        ];

        foreach ($suppliers as $data) {
            Supplier::create($data);
        }

        // Créer les produits BTP manuellement
        $products = [
            ['name' => 'Ciment CPJ 45', 'category' => 'Liants Hydrauliques', 'purchase_price' => 68.00, 'stock' => 500, 'description' => 'Ciment Portland composé standard BTP'],
            ['name' => 'Fer à béton HA8', 'category' => 'Acier & Ferraillage', 'purchase_price' => 6500.00, 'stock' => 18, 'description' => 'Armature béton armé diamètre 8mm'],
            ['name' => 'Sable de carrière 0/4', 'category' => 'Granulats & Sables', 'purchase_price' => 140.00, 'stock' => 200, 'description' => 'Sable pour mortier et enduit'],
            ['name' => 'Brique rouge T8', 'category' => 'Maçonnerie & Blocs', 'purchase_price' => 2.20, 'stock' => 4000, 'description' => 'Brique terre cuite standard'],
            ['name' => 'Parpaing creux 20cm', 'category' => 'Maçonnerie & Blocs', 'purchase_price' => 7.80, 'stock' => 500, 'description' => 'Bloc béton creux 20x20x40'],
            ['name' => 'Peinture vinylique blanc', 'category' => 'Peintures & Enduits', 'purchase_price' => 255.00, 'stock' => 60, 'description' => 'Peinture intérieure lavable'],
            ['name' => 'Disjoncteur 16A', 'category' => 'Électricité', 'purchase_price' => 45.00, 'stock' => 80, 'description' => 'Disjoncteur modulaire'],
            ['name' => 'Perceuse percussion 800W', 'category' => 'Outillage', 'purchase_price' => 680.00, 'stock' => 5, 'description' => 'Perceuse à percussion professionnelle'],
            ['name' => 'Plâtre de Paris', 'category' => 'Liants Hydrauliques', 'purchase_price' => 45.00, 'stock' => 150, 'description' => 'Plâtre pour enduits et scellements'],
            ['name' => 'Bétonnière 140L', 'category' => 'Outillage', 'purchase_price' => 2800.00, 'stock' => 2, 'description' => 'Bétonnière électrique chantier'],
        ];

        foreach ($products as $data) {
            Product::create($data);
        }

        // Créer des chantiers
        $chantiers = [
            ['name' => 'Chantier A - Port de Casablanca'],
            ['name' => 'Chantier B - Tour Mohammed VI Rabat'],
            ['name' => 'Chantier C - Marina Marrakech'],
        ];

        foreach ($chantiers as $c) {
            $chantierId = DB::table('chantiers')->insertGetId([
                'name' => $c['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Consommer des produits aléatoires sur ce chantier
            $randomProducts = Product::inRandomOrder()->take(5)->get();
            foreach ($randomProducts as $p) {
                DB::table('chantier_product')->insert([
                    'chantier_id' => $chantierId,
                    'product_id' => $p->id,
                    'quantity_consumed' => rand(5, 30),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

    }
}
