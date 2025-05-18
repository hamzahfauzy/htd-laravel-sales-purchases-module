<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Invoice;

class InvoiceResource extends Resource
{

    protected static ?string $navigationGroup = 'Sales & Purchases';
    protected static ?string $navigationLabel = 'Invoice';
    protected static ?string $navigationIcon = 'bx bx-file';
    protected static ?string $slug = 'sales-purchases/invoices';
    protected static ?string $routeGroup = 'sales-purchases';

    protected static $model = Invoice::class;

    public static function table()
    {
        return [
            'code' => [
                'label' => 'Code',
                '_searchable' => true
            ],
            'total_item' => [
                'label' => 'Total Item',
                '_searchable' => true
            ],
            'total_qty' => [
                'label' => 'Total Qty',
                '_searchable' => true
            ],
            'total_price' => [
                'label' => 'Total Price',
                '_searchable' => true
            ],
            'total_discount' => [
                'label' => 'Total Discount',
                '_searchable' => true
            ],
            'final_price' => [
                'label' => 'Final Price',
                '_searchable' => true
            ],
            'record_type' => [
                'label' => 'Type',
                '_searchable' => true
            ],
            'record_status' => [
                'label' => 'Status',
                '_searchable' => true
            ],
            '_action'
        ];
    }

    public static function form()
    {
        if(!static::$record)
        {
            static::$record = collect([
                'code' => 'INV-'.strtotime('now').'-'.rand(11111,99999)
            ]);
        }
        return [
            'Basic Information' => [
                'code' => [
                    'label' => 'Code',
                    'type' => 'text',
                    'placeholder' => 'Enter code',
                ],
                // 'total_item' => [
                //     'label' => 'Total Item',
                //     'type' => 'tel',
                //     'placeholder' => 'Enter Total Item',
                // ],
                // 'total_qty' => [
                //     'label' => 'Total Qty',
                //     'type' => 'tel',
                //     'placeholder' => 'Enter Total Qty',
                // ],
                // 'total_price' => [
                //     'label' => 'Total Price',
                //     'type' => 'tel',
                //     'placeholder' => 'Enter Total Price',
                // ],
                // 'total_discount' => [
                //     'label' => 'Total Discount',
                //     'type' => 'tel',
                //     'placeholder' => 'Enter Total Discount',
                // ],
                // 'final_price' => [
                //     'label' => 'Final Price',
                //     'type' => 'tel',
                //     'placeholder' => 'Enter Final Price',
                // ],
                'record_type' => [
                    'label' => 'Record Type',
                    'type' => 'select',
                    'options' => [
                        'PURCHASES' => 'PURCHASES',
                        'SALES' => 'SALES',
                    ],
                    'required' => true
                ],
                'record_status' => [
                    'label' => 'Status',
                    'type' => 'select',
                    'options' => [
                        'PUBLISH' => 'PUBLISH',
                        'DRAFT' => 'DRAFT',
                    ],
                    'required' => true
                ],
            ],
            'Item Information' => [

            ]
        ];
    }

    public static function detail()
    {
        return [
            'Basic Information' => [
                'code' => 'Code',
                'total_item' => 'Total Item',
                'total_qty' => 'Total Qty',
                'total_price' => 'Total Price',
                'total_discount' => 'Total Discount',
                'final_price' => 'Final Price',
                'record_type' => 'Record Type',
                'record_status' => 'Record Status',
            ],
        ];
    }
}
