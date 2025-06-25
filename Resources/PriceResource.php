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
            'purchase_price' => [
                'label' => 'Purchase Price',
                '_searchable' => true
            ],
            'amount_1' => [
                'label' => 'Price 1',
                '_searchable' => true
            ],
            'min_qty_1' => [
                'label' => 'Minium Qty 1',
                '_searchable' => true
            ],
            'amount_2' => [
                'label' => 'Price 2',
                '_searchable' => true
            ],
            'min_qty_2' => [
                'label' => 'Minium Qty 2',
                '_searchable' => true
            ],
            'amount_3' => [
                'label' => 'Price 3',
                '_searchable' => true
            ],
            'min_qty_3' => [
                'label' => 'Minium Qty 3',
                '_searchable' => true
            ],
            'amount_4' => [
                'label' => 'Price 4',
                '_searchable' => true
            ],
            'min_qty_4' => [
                'label' => 'Minium Qty 4',
                '_searchable' => true
            ],
            'amount_5' => [
                'label' => 'Price 5',
                '_searchable' => true
            ],
            'min_qty_5' => [
                'label' => 'Minium Qty 5',
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
                    'type' => 'select2',
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
                    'label' => 'Price 1',
                    'type' => 'tel',
                    'placeholder' => 'Enter price',
                ],
                'min_qty_1' => [
                    'label' => 'Minimum Qty 1',
                    'type' => 'tel',
                    'placeholder' => 'Enter minimum qty',
                ],
                'amount_2' => [
                    'label' => 'Price 2',
                    'type' => 'tel',
                    'placeholder' => 'Enter price',
                ],
                'min_qty_2' => [
                    'label' => 'Minimum Qty 2',
                    'type' => 'tel',
                    'placeholder' => 'Enter minimum qty',
                ],
                'amount_3' => [
                    'label' => 'Price 3',
                    'type' => 'tel',
                    'placeholder' => 'Enter price',
                ],
                'min_qty_3' => [
                    'label' => 'Minimum Qty 3',
                    'type' => 'tel',
                    'placeholder' => 'Enter minimum qty',
                ],
                'amount_4' => [
                    'label' => 'Price 4',
                    'type' => 'tel',
                    'placeholder' => 'Enter price',
                ],
                'min_qty_4' => [
                    'label' => 'Minimum Qty 4',
                    'type' => 'tel',
                    'placeholder' => 'Enter minimum qty',
                ],
                'amount_5' => [
                    'label' => 'Price 5',
                    'type' => 'tel',
                    'placeholder' => 'Enter price',
                ],
                'min_qty_5' => [
                    'label' => 'Minimum Qty 5',
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
                'amount_1' => 'Price 1',
                'min_qty_1' => 'Minimum Qty 1',
                'amount_2' => 'Price 2',
                'min_qty_2' => 'Minimum Qty 2',
                'amount_3' => 'Price 3',
                'min_qty_3' => 'Minimum Qty 3',
                'amount_4' => 'Price 4',
                'min_qty_4' => 'Minimum Qty 4',
                'amount_5' => 'Price 5',
                'min_qty_5' => 'Minimum Qty 5',
            ],
        ];
    }
}
