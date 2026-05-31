<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InventorySnapshot extends Command
{
    protected $signature = 'inventory:snapshot';

    protected $description = 'Record the total inventory value for today';

    public function handle()
    {
        $totalValue = Product::select(
            DB::raw('SUM(CAST(stock AS DECIMAL(15,2)) * CAST(purchase_price AS DECIMAL(15,2))) as total')
        )->first()->total ?? 0;

        DB::table('inventory_snapshots')->updateOrInsert(
            ['date' => now()->toDateString()],
            ['total_value' => $totalValue, 'updated_at' => now()]
        );

        $this->info('Snapshot enregistré : ' . number_format($totalValue, 2) . ' MAD');
        return 0;
    }
}
