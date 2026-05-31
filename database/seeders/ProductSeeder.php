<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Khwi la table products completement 9bel l'importation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Jrb l'chemin l'owl (database/data/) aw l'chemin t-tani (base_path direct)
        $csvFile = database_path('data/produits_btp_cleancopie.csv');
        if (!file_exists($csvFile)) {
            $csvFile = base_path('produits_btp_cleancopie.csv');
        }
        
        if (!file_exists($csvFile)) {
            $this->command->error("⚠️ L'fichier CSV introuvable! Gher 7etih f l'dossier principal dial stocket direct.");
            return;
        }

        $file = fopen($csvFile, 'r');
        
        // Skip dial l'ligne l'owl (les entêtes)
        $header = fgetcsv($file, 1000, ";");

        $count = 0;
        while (($data = fgetcsv($file, 1000, ";")) !== FALSE) {
            // Ila kant l'ligne khawya n'foutha
            if (!isset($data[0]) || empty(trim($data[0]))) continue;

            try {
                Product::create([
                    'name'           => trim($data[0]),                     // Nom
                    'category'       => trim($data[2] ?? 'Divers'),         // Catégorie
                    'purchase_price' => floatval($data[4] ?? 0),      // Prix d'achat
                    'stock'          => intval($data[5] ?? 0),         // Stock
                    'description'    => trim($data[7] ?? null),             // Description
                ]);
                $count++;
            } catch (\Exception $e) {
                continue;
            }
        }

        fclose($file);
        $this->command->info("Mabrouk! T-importaw {$count} produits dyal l'BTP f'la table products! 🚀🏗️");
    }
}