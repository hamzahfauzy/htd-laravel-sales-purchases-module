<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Invoice;

class InvoiceResource extends Resource {

    protected static ?string $navigationGroup = 'Sales & Purchases';
    protected static ?string $navigationLabel = 'Invoice';
    protected static ?string $navigationIcon = 'bx bx-category';
    protected static ?string $slug = 'sales-purchases/invoices';
    protected static ?string $routeGroup = 'sales-purchases';

    protected static $model = Invoice::class;

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