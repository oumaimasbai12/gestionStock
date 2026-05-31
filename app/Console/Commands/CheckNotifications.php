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

        $overdueExits = StockExit::with('customer')
            ->whereNull('deleted_at')
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->get();

        foreach ($overdueExits as $exit) {
            foreach ($admins as $admin) {
                if ($this->hasUnreadNotification($admin, 'overdue_invoice', 'exit_id', $exit->id)) {
                    continue;
                }

                $admin->notify(new OverdueInvoice($exit));
                $notified++;
            }
        }

        $criticalProducts = Product::whereColumn('stock', '<=', 'safety_stock')->get();

        foreach ($criticalProducts as $product) {
            foreach ($admins as $admin) {
                if ($this->hasUnreadNotification($admin, 'critical_stock', 'product_id', $product->id)) {
                    continue;
                }

                $admin->notify(new CriticalStockAlert($product));
                $notified++;
            }
        }

        $this->info($notified . ' notification(s) envoyée(s) à ' . $admins->count() . ' admin(s).');
        return 0;
    }

    private function hasUnreadNotification(User $admin, string $type, string $key, int $id): bool
    {
        return $admin->unreadNotifications()
            ->where('data->type', $type)
            ->where('data->'.$key, $id)
            ->exists();
    }
}
