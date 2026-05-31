<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chantier extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function exits()
    {
        return $this->hasMany(StockExit::class);
    }
}
