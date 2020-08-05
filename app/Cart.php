<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    /**
     * @var array
     */
    protected $fillable =[
        'product_id',
        'cart_key'
    ];

    /**
     * @return HasMany
     */
    public function products(){
        return $this->hasMany(Product::class,'product_id');
    }
}
