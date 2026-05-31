<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_id',
        'customer_type',
        'name',
        'email',
        'address',
        'phone',
        'ice',
    ];

    public function exits()
    {
        return $this->hasMany(\App\Models\StockExit::class);
    }
}
