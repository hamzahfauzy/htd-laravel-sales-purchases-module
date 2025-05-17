<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Payment;

class PaymentResource extends Resource {

    protected static ?string $navigationGroup = 'Sales & Purchases';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?string $navigationIcon = 'bx bx-category';
    protected static ?string $slug = 'sales-purchases/payments';
    protected static ?string $routeGroup = 'sales-purchases';

    protected static $model = Payment::class;

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