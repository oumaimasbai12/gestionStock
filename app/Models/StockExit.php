<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockExit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'customer_id',
        'chantier_id',
        'quantity',
        'unit_price',
        'paid_amount',
        'payment_status',
        'document',
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class)->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class)->withTrashed();
    }

    public function chantier()
    {
        return $this->belongsTo(\App\Models\Chantier::class);
    }
}
