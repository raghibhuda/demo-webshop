<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'product_id',
        'total_amount',
        'net_amount',
        'shipping_cost',
        'quantity',
        'status'
    ];

    /**
     * @return HasMany
     */
    public function products(){
        return $this->hasMany(Product::class,'product_id');
    }
}
