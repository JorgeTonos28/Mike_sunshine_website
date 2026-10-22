<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['product_id', 'title', 'quantity', 'unit_price_minor', 'currency', 'awaiting_availability'];

    protected function casts(): array
    {
        return ['awaiting_availability' => 'boolean'];
    }
}
