<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\StockExit;
use App\Models\User;
use App\Notifications\CriticalStockAlert;
use App\Notifications\OverdueInvoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckNotifications extends Command
{
    protected $signature = 'notifications:check';

    protected $description = 'Check for overdue invoices and critically low stock, then notify admins';

    public function handle()
    {
        $admins = User::role('admin')->get();
        if ($admins->isEmpty()) {
            $this->warn('Aucun administrateur trouvé.');
            return 1;
        }

        $notified = 0;

        // 1. Overdue invoices — pending unpaid/partial exits created > 7 days ago
        $overdueExits = StockExit::with('customer')
            ->whereNull('deleted_at')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->get();

        foreach ($overdueExits as $exit) {
            foreach ($admins as $admin) {
                $admin->notify(new OverdueInvoice($exit));
                $notified++;
            }
        }

        // 2. Critical stock — products where stock <= safety_stock
        $criticalProducts = Product::whereColumn('stock', '<=', 'safety_stock')->get();

        foreach ($criticalProducts as $product) {
            foreach ($admins as $admin) {
                $admin->notify(new CriticalStockAlert($product));
                $notified++;
            }
        }

        $this->info($notified . ' notification(s) envoyée(s) à ' . $admins->count() . ' admin(s).');
        return 0;
    }
}
