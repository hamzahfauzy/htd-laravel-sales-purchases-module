<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Supplier;

class SupplierResource extends Resource {

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Supplier';
    protected static ?string $navigationIcon = 'bx bx-category';
    protected static ?string $slug = 'master/suppliers';
    protected static ?string $routeGroup = 'master';

    protected static $model = Supplier::class;

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