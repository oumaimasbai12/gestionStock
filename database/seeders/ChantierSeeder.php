<?php

namespace Database\Seeders;

use App\Models\Chantier;
use Illuminate\Database\Seeder;

class ChantierSeeder extends Seeder
{
    public function run(): void
    {
        $chantiers = [
            'Chantier Casablanca — Tour A',
            'Chantier Rabat — Résidence Al Amal',
            'Chantier Marrakech — Villa Premium',
            'Chantier Tanger — Zone Industrielle',
            'Chantier Fès — Extension Route Nationale',
        ];

        foreach ($chantiers as $name) {
            Chantier::firstOrCreate(['name' => $name]);
        }
    }
}
