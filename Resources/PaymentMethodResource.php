<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\PaymentMethod;

class PaymentMethodResource extends Resource
{

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Payment Method';
    protected static ?string $navigationIcon = 'bx bx-wallet';
    protected static ?string $slug = 'master/payment-methods';
    protected static ?string $routeGroup = 'master';

    protected static $model = PaymentMethod::class;

    public static function table()
    {
        return [
            'name' => [
                'label' => 'Name',
                '_searchable' => true
            ],
            'description' => [
                'label' => 'Description',
                '_searchable' => true
            ],
            'creator.name' => [
                'label' => 'Created By',
                '_searchable' => false
            ],
            'created_at' => [
                'label' => 'Date',
                '_searchable' => false
            ],
            '_action'
        ];
    }

    public static function form()
    {
        return [
            'Basic Information' => [
                'name' => [
                    'label' => 'Name',
                    'type' => 'text',
                    'placeholder' => 'Enter name',
                    'required' => true,
                ],
                'description' => [
                    'label' => 'Description',
                    'type' => 'textarea',
                    'placeholder' => 'Enter description',
                ],
            ]
        ];
    }

    public static function detail()
    {
        return [
            'Basic Information' => [
                'name' => 'Name',
                'description' => 'Description',
            ],
        ];
    }
}
