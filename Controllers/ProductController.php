<?php

namespace App\Modules\SalesPurchases\Controllers;

use App\Modules\SalesPurchases\Models\Product;

class ProductController
{
    function get()
    {
        $term = request('term', '');
        $items = Product::with('prices')->where('name','LIKE', "%$term%")
                        ->orWhere('sku','LIKE', "%$term%")
                        ->orWhere('code','LIKE', "%$term%")
                        ->limit(20)
                        ->get();

        return $items;
    }
}