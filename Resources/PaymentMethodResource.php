<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\PaymentMethod;

class PaymentMethodResource extends Resource {

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Payment Method';
    protected static ?string $navigationIcon = 'bx bx-category';
    protected static ?string $slug = 'master/payment-methods';
    protected static ?string $routeGroup = 'master';

    protected static $model = PaymentMethod::class;

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