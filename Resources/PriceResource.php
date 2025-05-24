<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\Inventory\Models\Item;
use App\Modules\SalesPurchases\Models\Price;

class PriceResource extends Resource
{

    protected static ?string $navigationGroup = 'Sales & Purchases';
    protected static ?string $navigationLabel = 'Price';
    protected static ?string $navigationIcon = 'bx bx-dollar';
    protected static ?string $slug = 'sales-purchases/prices';
    protected static ?string $routeGroup = 'sales-purchases';

    protected static $model = Price::class;

    public static function mount()
    {
        static::addScripts([
            asset('modules/salespurchases/js/price-resource.js')
        ]);
    }

    public static function table()
    {
        return [
            'product.completeName' => [
                'label' => 'Product',
                '_searchable' => true
            ],
            'unit' => [
                'label' => 'Unit',
                '_searchable' => true
            ],
            'purchase_price' => [
                'label' => 'Purchase Price',
                '_searchable' => true
            ],
            'amount_1' => [
                'label' => 'Price',
                '_searchable' => true
            ],
            'min_qty_1' => [
                'label' => 'Minium Qty',
                '_searchable' => true
            ],
            'creator.name' => [
                'label' => 'Created By',
                '_searchable' => true
            ],
            'created_at' => [
                'label' => 'Date'
            ],
            '_action'
        ];
    }

    public static function form()
    {
        $items = Item::get();
        $itemOptions = [];
        foreach($items as $item)
        {
            $itemOptions[$item->id] = $item->completeName;
        }

        $units = [];
        if(static::$record)
        {
            $units[static::$record?->unit] = static::$record?->unit;
            foreach(static::$record->product->conversions as $conversion)
            {
                $units[$conversion->unit] = $conversion->unit;
            }
        }

        return [
            'Basic Information' => [
                'product_id' => [
                    'label' => 'Product',
                    'type' => 'select',
                    'options' => $itemOptions,
                    'placeholder' => 'Choose Product',
                    'required' => true,
                ],
                'unit' => [
                    'label' => 'Unit',
                    'type' => 'select',
                    'options' => $units,
                    'placeholder' => 'Choose unit',
                    'required' => true,
                ],
                'purchase_price' => [
                    'label' => 'Purchase Price',
                    'type' => 'tel',
                    'placeholder' => 'Enter price',
                ],
                'amount_1' => [
                    'label' => 'Price',
                    'type' => 'tel',
                    'placeholder' => 'Enter price',
                ],
                'min_qty_1' => [
                    'label' => 'Minimum Qty',
                    'type' => 'tel',
                    'placeholder' => 'Enter minimum qty',
                ],
            ]
        ];
    }

    public static function detail()
    {
        return [
            'Basic Information' => [
                'product.completeName' => 'Name',
                'unit' => 'Unit',
                'purchase_price' => 'Purchase Price',
                'amount_1' => 'Price',
                'min_qty_1' => 'Minimum Qty',
            ],
        ];
    }
}
