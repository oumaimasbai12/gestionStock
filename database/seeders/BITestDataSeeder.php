<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Chantier;
use App\Models\StockExit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BITestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean all tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        StockExit::truncate();
        Chantier::truncate();
        Product::truncate();
        Customer::truncate();
        Supplier::truncate();
        User::truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Create Roles
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleStorekeeper = Role::create(['name' => 'storekeeper']);
        $roleSiteManager = Role::create(['name' => 'site_manager']);

        // 3. Create Chantiers first so we can assign a supervisor to one of them
        $cAnfa = Chantier::create(['name' => 'Chantier Anfa Place']);
        $cNouveau = Chantier::create(['name' => 'Chantier Nouveau Extension']);
        $cRabat = Chantier::create(['name' => 'Chantier Rabat Ville']);
        $cTanger = Chantier::create(['name' => 'Chantier Tanger Med']);
        $cOther = Chantier::create(['name' => 'Autres Chantiers']);

        // 4. Create the requested role-based users
        
        // Admin
        $admin = new User();
        $admin->name = 'Stocket Admin';
        $admin->email = 'admin@stocket.com';
        $admin->password = bcrypt('12345678');
        $admin->save();
        $admin->assignRole($roleAdmin);

        // Storekeeper
        $storekeeper = new User();
        $storekeeper->name = 'Sébastien Storekeeper';
        $storekeeper->email = 'storekeeper@stocket.com';
        $storekeeper->password = bcrypt('12345678');
        $storekeeper->save();
        $storekeeper->assignRole($roleStorekeeper);

        // Site Manager (Chantier Supervisor) - Assigned to Chantier Anfa Place
        $manager = new User();
        $manager->name = 'Mohamed Site Manager';
        $manager->email = 'manager@stocket.com';
        $manager->password = bcrypt('12345678');
        $manager->chantier_id = $cAnfa->id;
        $manager->save();
        $manager->assignRole($roleSiteManager);

        // 5. Create Customers & Suppliers
        Customer::factory(10)->create();
        $suppliers = [
            ['nit' => '123456789', 'name' => 'Tech Solutions BTP', 'phone' => '0522123456', 'email' => 'contact@techsolutions.com', 'address' => 'Boulevard Anfa, Casablanca'],
            ['nit' => '987654321', 'name' => 'CompuParts Maroc', 'phone' => '0537987654', 'email' => 'ventas@compuparts.com', 'address' => 'Avenue de France, Rabat'],
        ];
        foreach ($suppliers as $data) {
            Supplier::create($data);
        }

        // 6. Load products from CSV and adjust their stocks to sum exactly to 1,170,446 MAD
        $csvFile = database_path('data/produits_btp_cleancopie.csv');
        if (!file_exists($csvFile)) {
            $csvFile = base_path('produits_btp_cleancopie.csv');
        }

        if (!file_exists($csvFile)) {
            throw new \Exception("CSV file not found!");
        }

        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file, 1000, ";");

        $productsData = [];
        while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
            if (!isset($data[0]) || empty(trim($data[0]))) continue;
            $productsData[] = [
                'name'           => trim($data[0]),
                'category'       => trim($data[2] ?? 'Divers'),
                'purchase_price' => floatval($data[4] ?? 0),
                'stock'          => intval($data[5] ?? 0),
                'description'    => trim($data[7] ?? null),
            ];
        }
        fclose($file);

        $totalProducts = count($productsData);
        $targetStockValue = 1170446.00;
        $currentSum = 0;

        $createdProducts = [];
        for ($i = 0; $i < $totalProducts; $i++) {
            $pData = $productsData[$i];
            
            // Set stock level
            if ($i < 5) {
                $stock = 5; // Alert state
            } else {
                $stock = 100; // Healthy state
            }
            
            $purchasePrice = $pData['purchase_price'];
            if ($purchasePrice <= 0) {
                $purchasePrice = 10.00;
            }

            if ($i == $totalProducts - 1) {
                // Adjust last product to match target sum exactly
                $remaining = $targetStockValue - $currentSum;
                $stock = 1000;
                $purchasePrice = $remaining / 1000;
            }

            $currentSum += ($stock * $purchasePrice);

            $product = Product::create([
                'name'           => $pData['name'],
                'category'       => $pData['category'],
                'purchase_price' => $purchasePrice,
                'stock'          => $stock,
                'description'    => $pData['description'],
            ]);
            $createdProducts[] = $product;
        }

        // 7. Create stock exits matching the BI values
        $pLiants = Product::where('category', 'Liants Hydrauliques')->first() ?? $createdProducts[0];
        $pAcier = Product::where('category', 'Acier & Ferraillage')->first() ?? $createdProducts[1];
        $pOther = Product::whereNotIn('category', ['Liants Hydrauliques', 'Acier & Ferraillage'])->first() ?? $createdProducts[2];

        $customer = Customer::first();

        // System of exits:
        $exits = [
            // Exit 1: Chantier Anfa Place, Category: Liants Hydrauliques, value: 100,800.00, paid: 50,000.00, status: partial
            [
                'product_id' => $pLiants->id,
                'chantier_id' => $cAnfa->id,
                'quantity' => 100,
                'unit_price' => 1008.00,
                'paid_amount' => 50000.00,
                'payment_status' => 'partial',
                'document' => 'BL-ANFA-001',
            ],
            // Exit 2: Chantier Nouveau Extension, Category: Acier & Ferraillage, value: 91,988.80, paid: 40,000.00, status: partial
            [
                'product_id' => $pAcier->id,
                'chantier_id' => $cNouveau->id,
                'quantity' => 100,
                'unit_price' => 919.888, // 100 * 919.888 = 91,988.80
                'paid_amount' => 40000.00,
                'payment_status' => 'partial',
                'document' => 'BL-NOV-001',
            ],
            // Exit 3: Chantier Nouveau Extension, Category: Other, value: 4,011.20, paid: 0.00, status: unpaid
            [
                'product_id' => $pOther->id,
                'chantier_id' => $cNouveau->id,
                'quantity' => 10,
                'unit_price' => 401.12, // 10 * 401.12 = 4,011.20
                'paid_amount' => 0.00,
                'payment_status' => 'unpaid',
                'document' => 'BL-NOV-002',
            ],
            // Exit 4: Chantier Rabat Ville, Category: Other, value: 45,200.00, paid: 10,000.00, status: partial
            [
                'product_id' => $pOther->id,
                'chantier_id' => $cRabat->id,
                'quantity' => 50,
                'unit_price' => 904.00, // 50 * 904 = 45,200.00
                'paid_amount' => 10000.00,
                'payment_status' => 'partial',
                'document' => 'BL-RAB-001',
            ],
            // Exit 5: Chantier Tanger Med, Category: Other, value: 32,428.86, paid: 0.00, status: unpaid
            [
                'product_id' => $pOther->id,
                'chantier_id' => $cTanger->id,
                'quantity' => 30,
                'unit_price' => 1080.962, // 30 * 1080.962 = 32,428.86
                'paid_amount' => 0.00,
                'payment_status' => 'unpaid',
                'document' => 'BL-TNG-001',
            ],
            // Exit 6: Chantier Tanger Med, Category: Liants Hydrauliques, value: 7,971.14, paid: 0.00, status: unpaid
            [
                'product_id' => $pLiants->id,
                'chantier_id' => $cTanger->id,
                'quantity' => 10,
                'unit_price' => 797.114, // 10 * 797.114 = 7,971.14
                'paid_amount' => 0.00,
                'payment_status' => 'unpaid',
                'document' => 'BL-TNG-002',
            ],
            // Exit 7: Autres Chantiers, Category: Liants Hydrauliques, value: 5,065.00, paid: 2,811.00, status: partial
            [
                'product_id' => $pLiants->id,
                'chantier_id' => $cOther->id,
                'quantity' => 5,
                'unit_price' => 1013.00, // 5 * 1013 = 5,065.00
                'paid_amount' => 2811.00,
                'payment_status' => 'partial',
                'document' => 'BL-OTH-001',
            ],
        ];

        foreach ($exits as $exit) {
            StockExit::create([
                'product_id' => $exit['product_id'],
                'customer_id' => $customer->id ?? null,
                'chantier_id' => $exit['chantier_id'],
                'quantity' => $exit['quantity'],
                'unit_price' => $exit['unit_price'],
                'paid_amount' => $exit['paid_amount'],
                'payment_status' => $exit['payment_status'],
                'document' => $exit['document'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
