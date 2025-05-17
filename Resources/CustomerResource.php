<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Customer;

class CustomerResource extends Resource {

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Customer';
    protected static ?string $navigationIcon = 'bx bx-category';
    protected static ?string $slug = 'master/customers';
    protected static ?string $routeGroup = 'master';

    protected static $model = Customer::class;

    public static function table()
    {
        return [
        ];
    }

    public static function form()
    {
        return [];
    }

    public static function detail()
    {
        return [];
    }
}