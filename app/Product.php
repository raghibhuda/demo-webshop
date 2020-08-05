<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'price'
    ];

    /**
     * @return BelongsTo
     */
    public function cart(){
        return $this->belongsTo(Cart::class);
    }

    /**
     * @return BelongsTo
     */
    public function order(){
        return $this->belongsTo(Order::class);
    }
}
