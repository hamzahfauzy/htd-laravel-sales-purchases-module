<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Inventory\Models\Item;

class Product extends Item
{
    
    function prices()
    {
        return $this->hasMany(Price::class,'product_id','id');
    }
}