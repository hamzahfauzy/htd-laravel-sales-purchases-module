<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Invoice;

class InvoiceResource extends Resource
{

    protected static ?string $navigationGroup = 'Sales & Purchases';
    protected static ?string $navigationLabel = 'Invoice';
    protected static ?string $navigationIcon = 'bx bx-category';
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
                'label' => 'Record Type',
                '_searchable' => true
            ],
            'record_status' => [
                'label' => 'Record Status',
                '_searchable' => true
            ],
            '_action'
        ];
    }

    public static function form()
    {
        return [
            'Basic Information' => [
                'code' => [
                    'label' => 'Code',
                    'type' => 'text',
                    'placeholder' => 'Enter your code'
                ],
                'total_item' => [
                    'label' => 'Total Item',
                    'type' => 'number',
                    'placeholder' => 'Enter your Total Item',
                ],
                'total_qty' => [
                    'label' => 'Total Qty',
                    'type' => 'number',
                    'placeholder' => 'Enter your Total Qty',
                ],
                'total_price' => [
                    'label' => 'Total Price',
                    'type' => 'number',
                    'placeholder' => 'Enter your Total Price',
                ],
                'total_discount' => [
                    'label' => 'Total Discount',
                    'type' => 'number',
                    'placeholder' => 'Enter your Total Discount',
                ],
                'final_price' => [
                    'label' => 'Final Price',
                    'type' => 'number',
                    'placeholder' => 'Enter your Final Price',
                ],
                'record_type' => [
                    'label' => 'Record Type',
                    'type' => 'text',
                    'placeholder' => 'Enter your Record Type',
                ],
                'record_status' => [
                    'label' => 'Record Status',
                    'type' => 'text',
                    'placeholder' => 'Enter your Record Status',
                ],

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
