<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Printer;

class PrinterResource extends Resource {

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Printer';
    protected static ?string $navigationIcon = 'bx bx-category';
    protected static ?string $slug = 'master/printers';
    protected static ?string $routeGroup = 'master';

    protected static $model = Printer::class;

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