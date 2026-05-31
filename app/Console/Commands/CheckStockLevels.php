<?php

namespace App\Console\Commands;

use App\Mail\LowStockAlert;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckStockLevels extends Command
{
    protected $signature = 'app:check-stock-levels';

    protected $description = 'Check products with low stock and email admin';

    public function handle()
    {
        $lowStockProducts = Product::whereColumn('stock', '<=', 'alert_quantity')->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('Aucun produit en stock faible.');
            return 0;
        }

        $admins = User::role('admin')->get();

        if ($admins->isEmpty()) {
            $this->warn('Aucun administrateur trouvé pour envoyer l\'alerte.');
            return 1;
        }

        foreach ($admins as $admin) {
            Mail::to($admin)->send(new LowStockAlert($lowStockProducts));
        }

        $this->info('Alerte stock faible envoyée à ' . $admins->count() . ' administrateur(s).');
        return 0;
    }
}
