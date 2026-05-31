<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CriticalStockAlert extends Notification
{
    use Queueable;

    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'critical_stock',
            'title' => 'Stock critique',
            'message' => $this->product->name . ' n\'a plus que ' . $this->product->stock . ' unités en stock (seuil: ' . $this->product->safety_stock . ').',
            'product_id' => $this->product->id,
            'stock' => $this->product->stock,
            'url' => route('products.show', $this->product),
        ];
    }
}
