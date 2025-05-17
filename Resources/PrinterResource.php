<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Printer;

class PrinterResource extends Resource
{

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Printer';
    protected static ?string $navigationIcon = 'bx bx-category';
    protected static ?string $slug = 'master/printers';
    protected static ?string $routeGroup = 'master';

    protected static $model = Printer::class;

    public static function table()
    {
        return [
            'name' => [
                'label' => 'Name',
                '_searchable' => true
            ],
            'type' => [
                'label' => 'Type',
                '_searchable' => true
            ],
            'connection' => [
                'label' => 'connection',
                '_searchable' => true
            ],
            'paper_size' => [
                'label' => 'Paper Size',
                '_searchable' => true
            ],
            'character_set' => [
                'label' => 'Character Set',
                '_searchable' => true
            ],
            'auto_cut' => [
                'label' => 'Auto Cut',
                '_searchable' => true
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
                    'placeholder' => 'Enter your name',
                    'required' => true,
                ],
                'type' => [
                    'label' => 'type',
                    'type' => 'text',
                    'placeholder' => 'Enter your type',
                ],
                'connection' => [
                    'label' => 'connection',
                    'type' => 'text',
                    'placeholder' => 'Enter your connection',
                ],
                'paper_size' => [
                    'label' => 'Paper Size',
                    'type' => 'text',
                    'placeholder' => 'Enter your Paper Size',
                ],
                'character_set' => [
                    'label' => 'Character Set',
                    'type' => 'text',
                    'placeholder' => 'Enter your Character Set',
                ],
                'auto_cut' => [
                    'label' => 'Auto Cut',
                    'type' => 'text',
                    'placeholder' => 'Enter your Auto Cut',
                ],
            ]
        ];
    }

    public static function detail()
    {
        return [
            'Basic Information' => [
                'name' => 'Name',
                'type' => 'Type',
                'connection' => 'Connection',
                'paper_size' => 'Paper Size',
                'character_set' => 'Character Set',
                'auto_cut' => 'Auto Cut',
            ],
        ];
    }
}
