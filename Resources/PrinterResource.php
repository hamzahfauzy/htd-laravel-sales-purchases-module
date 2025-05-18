<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Printer;

class PrinterResource extends Resource
{

    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Printer';
    protected static ?string $navigationIcon = 'bx bx-printer';
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
            'connection_string' => [
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
            'creator.name' => [
                'label' => 'Created By',
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
                    'placeholder' => 'Enter name',
                    'required' => true,
                ],
                'type' => [
                    'label' => 'type',
                    'type' => 'select',
                    'options' => [
                        'WINDOWS' => 'WINDOWS',
                        'USB' => 'USB',
                        'NETWORK' => 'NETWORK', 
                        'BLUETOOTH' => 'BLUETOOTH',
                        'RAWBT' => 'RAWBT'
                    ],
                    'required' => true
                ],
                'connection_string' => [
                    'label' => 'connection',
                    'type' => 'text',
                    'placeholder' => 'Enter connection',
                    'required' => true
                ],
                'paper_size' => [
                    'label' => 'Paper Size',
                    'type' => 'select',
                    'options' => [
                        '32' => '58mm',
                        '48' => '80mm', 
                    ],
                    'required' => true
                ],
                'character_set' => [
                    'label' => 'Character Set',
                    'type' => 'text',
                    'placeholder' => 'Enter Character Set',
                ],
                'auto_cut' => [
                    'label' => 'Auto Cut',
                    'type' => 'select',
                    'options' => [
                        'YES' => 'YES', 
                        'NO' => 'NO'
                    ],
                    'required' => true
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
                'connection_string' => 'Connection',
                'paper_size' => 'Paper Size',
                'character_set' => 'Character Set',
                'auto_cut' => 'Auto Cut',
            ],
        ];
    }
}
