<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Customer;

class CustomerResource extends Resource
{

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Customer';
    protected static ?string $navigationIcon = 'bx bx-user';
    protected static ?string $slug = 'master/customers';
    protected static ?string $routeGroup = 'master';

    protected static $model = Customer::class;

    public static function table()
    {
        return [
            'code' => [
                'label' => 'Code',
                '_searchable' => true
            ],
            'name' => [
                'label' => 'Name',
                '_searchable' => true
            ],
            'email' => [
                'label' => 'Email',
                '_searchable' => true
            ],
            'phone' => [
                'label' => 'Phone',
                '_searchable' => true
            ],
            'address' => [
                'label' => 'Address',
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
                    'placeholder' => 'Enter code'
                ],
                'name' => [
                    'label' => 'Name',
                    'type' => 'text',
                    'placeholder' => 'Enter name',
                    'required' => true,
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'email',
                    'placeholder' => 'Enter email',
                ],
                'phone' => [
                    'label' => 'Phone',
                    'type' => 'text',
                    'placeholder' => 'Enter phone',
                ],
                'address' => [
                    'label' => 'Address',
                    'type' => 'textarea',
                    'placeholder' => 'Enter address',
                ],

            ]
        ];
    }

    public static function detail()
    {
        return [
            'Basic Information' => [
                'code' => 'Code',
                'name' => 'Name',
                'email' => 'Email',
                'phone' => 'Phone',
                'address' => 'Address',
            ],
        ];
    }
}
