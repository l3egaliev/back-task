<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ration extends Model
{
    protected $fillable = [
        'order_id',
        'cooking_date',
        'delivery_date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
