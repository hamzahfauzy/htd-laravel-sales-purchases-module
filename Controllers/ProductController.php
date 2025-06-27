<?php

namespace App\Modules\SalesPurchases\Controllers;

use App\Libraries\DataTable;
use App\Modules\SalesPurchases\Models\Price;
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

    function datatable()
    {
        $model = new Price;
        $datatable = new DataTable($model, [
            'product.completeName' => [
                'label' => 'Product',
                '_searchable' => [
                    'product.name',
                    'product.code',
                    'product.sku',
                ]
            ],
            'unit' => [
                'label' => 'Unit',
                '_searchable' => true
            ],
            'amount_1' => [
                'label' => 'Price 1',
                '_searchable' => true
            ],
            'amount_2' => [
                'label' => 'Price 2',
                '_searchable' => true
            ],
            'amount_3' => [
                'label' => 'Price 3',
                '_searchable' => true
            ],
            'amount_4' => [
                'label' => 'Price 4',
                '_searchable' => true
            ],
            'amount_5' => [
                'label' => 'Price 5',
                '_searchable' => true
            ],
            'product.code' => [
                'label' => 'Kode',
                '_searchable' => true
            ]
        ]);

        return $datatable->response();
    }
}