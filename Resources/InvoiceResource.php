<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Libraries\Components\Button;
use App\Libraries\Components\Delete;
use App\Modules\Base\Models\Profile;
use App\Modules\Inventory\Models\Item;
use App\Modules\Inventory\Models\ItemLog;
use App\Modules\SalesPurchases\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceResource extends Resource
{

    protected static ?string $navigationGroup = 'Sales & Purchases';
    protected static ?string $navigationLabel = 'Invoice';
    protected static ?string $navigationIcon = 'bx bx-file';
    protected static ?string $slug = 'sales-purchases/invoices';
    protected static ?string $routeGroup = 'sales-purchases';

    protected static $model = Invoice::class;

    public static function mount()
    {
        if(request()->routeIs('*.edit') || request()->routeIs('*.create'))
        {
            static::addScripts([
                asset('modules/salespurchases/js/autoNumeric.min.js'),
                asset('modules/salespurchases/js/invoice-item.js')
            ]);

            if(request()->routeIs('*.edit'))
            {
                $id = request('id');
                $invoice = static::$model::where('id', $id)->where('record_status','DRAFT')->first();
                if(!$invoice)
                {
                    $url = route('sales-purchases.sales-purchases/invoices.detail', $id);
                    header('location:'.$url);
                    die;
                }
            }
        }
    }

    public static function getNavShortcut()
    {
        return [
            [
                'url' => url('/pos'),
                'label' => 'Pos Panel',
                'icon' => 'bx bxs-registered'
            ]
        ];
    }

    public static function table()
    {
        return [
            'code' => [
                'label' => 'Code',
                '_searchable' => true
            ],
            'total_item' => [
                'label' => 'Total Item',
                '_searchable' => true
            ],
            'total_qty' => [
                'label' => 'Total Qty',
                '_searchable' => true
            ],
            'total_price' => [
                'label' => 'Total Price',
                '_searchable' => true
            ],
            'total_discount' => [
                'label' => 'Total Discount',
                '_searchable' => true
            ],
            'final_price' => [
                'label' => 'Final Price',
                '_searchable' => true
            ],
            'record_type' => [
                'label' => 'Type',
                '_searchable' => true
            ],
            'record_status' => [
                'label' => 'Status',
                '_searchable' => true
            ],
            'profile.0.name' => [
                'label' => 'Customer/Supplier',
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
        if(!static::$record)
        {
            // static::$record = collect([
            //     'code' => 'INV-'.strtotime('now').'-'.rand(11111,99999),
            //     'invoice_discount' => 0,
            // ]);

            static::$record = new static::$model;
            static::$record->code = 'INV-'.strtotime('now').'-'.rand(11111,99999);
            static::$record->invoice_discount = 0;
            static::$record->items = [];
        }
        else
        {
            static::$record->profile_id = static::$record->profile && isset(static::$record->profile[0]) ? static::$record->profile[0]->id : '';
        }

        $profiles = Profile::whereIn('record_type', ['SUPPLIER','CUSTOMER'])->pluck('name', 'id');
        
        return [
            'Basic Information' => [
                'code' => [
                    'label' => 'Code',
                    'type' => 'text',
                    'placeholder' => 'Enter code',
                ],
                'total_item' => [
                    'label' => 'Total Item',
                    'type' => 'text',
                    'placeholder' => 'Total Item',
                    'readonly' => 'readonly'
                ],
                'total_qty' => [
                    'label' => 'Total Qty',
                    'type' => 'text',
                    'placeholder' => 'Total Qty',
                    'readonly' => 'readonly'
                ],
                'total_price' => [
                    'label' => 'Total Price',
                    'type' => 'text',
                    'placeholder' => 'Total Price',
                    'readonly' => 'readonly'
                ],
                'invoice_discount' => [
                    'label' => 'Discount',
                    'type' => 'tel',
                ],
                'final_price' => [
                    'label' => 'Final Price',
                    'type' => 'text',
                    'placeholder' => 'Final Price',
                    'readonly' => 'readonly'
                ],
                'profile_id' => [
                    'label' => 'Customer/Supplier',
                    'type' => 'select2',
                    'options' => $profiles,
                    'placeholder' => 'Choose Data',
                ],
                'record_type' => [
                    'label' => 'Record Type',
                    'type' => 'select',
                    'options' => [
                        'PURCHASES' => 'PURCHASES',
                        'SALES' => 'SALES',
                    ],
                    'required' => true
                ],
                'record_status' => [
                    'label' => 'Status',
                    'type' => 'select',
                    'options' => [
                        'DRAFT' => 'DRAFT',
                        'PUBLISH' => 'PUBLISH',
                    ],
                    'required' => true
                ],
            ],
            'Item Information' => view('sales-purchases::invoices.item-field', [
                'data' => static::$record
            ])->render()
        ];
    }

    public static function detail()
    {
        return [
            'Basic Information' => [
                'code' => 'Code',
                'total_item' => 'Total Item',
                'total_qty' => 'Total Qty',
                'total_price' => 'Total Price',
                'invoice_discount' => 'Invoice Discount',
                'item_discount' => 'Item Discount',
                'total_discount' => 'Total Discount',
                'final_price' => 'Final Price',
                'profile.0.name' => 'Customer/Supplier',
                'record_type' => 'Record Type',
                'record_status' => 'Record Status',
            ],
            'Item List' => view('sales-purchases::invoices.detail-item', [
                'data' => static::$record
            ])->render()
        ];
    }

    public static function detailHeader()
    {
        $button = [
            (new Button([
                'url' => static::getPageRoute('index'),
                'class' => 'btn btn-sm btn-outline-secondary',
                'label' => 'Back',
                'icon' => 'fas fa-fw fa-arrow-left'
            ]))
                ->routeName(static::getPageRouteName('index'))
                ->render()
        ];

        if(static::$record->record_status == 'DRAFT')
        {
            $button[] = (new Button([
                    'url' => static::getPageRoute('edit', ['id' => static::$record?->id]),
                    'class' => 'btn btn-sm btn-warning',
                    'label' => 'Edit',
                    'icon' => 'fas fa-fw fa-pencil'
                ]))
                    ->routeName(static::getPageRouteName('edit'))
                    ->render();
        }

        return [
            'title' => 'Detail ' . static::$navigationLabel,
            'button' => $button
        ];
    }

    public static function getAction($d)
    {
        $buttons = [
            'view' => (new Button([
                'url' => static::getPageRoute('detail', ['id' => $d->id]),
                'label' => 'Detail',
                'class' => 'dropdown-item',
                'icon' => 'fas fa-fw fa-eye'
            ]))
            ->routeName(static::getPageRouteName('detail'))
            ->render(),
            'edit' => (new Button([
                'url' => static::getPageRoute('edit', ['id' => $d->id]),
                'label' => 'Edit',
                'class' => 'dropdown-item',
                'icon' => 'fas fa-fw fa-pencil'
            ]))
            ->routeName(static::getPageRouteName('edit'))
            ->render(),
            'delete' => (new Delete([
                'url' => static::getPageRoute('delete', ['id' => $d->id]),
                'label' => 'Delete',
                'class' => 'dropdown-item text-danger delete-record',
                'icon' => 'fas fa-fw fa-trash'
            ]))
            ->routeName(static::getPageRouteName('delete'))
            ->render()
        ];

        if($d->record_status != 'DRAFT')
        {
            unset($buttons['edit']);
        }

        return view('libraries.components.actions', compact('buttons'))->render();
    }

    public static function afterCreate(Request $request, $data)
    {
        foreach($request->items as $item)
        {
            $data->items()->create($item);

            if($data->record_status == 'PUBLISH')
            {

                $_item = Item::where('id', $item['product_id'])->with('conversions')->first();
                $amount = $item['qty'];
                $description = $data->record_type.' #' . $data->code;
                if($_item->unit != $item['unit'])
                {
                    $conversion = $_item->conversions->where('unit', $item['unit'])->first();
                    $amount = $amount * $conversion->value;
                    $description .= ' - conversion from '. $item['qty'] . ' ' . $item['unit'] . ' to ' . $amount .' '.$_item->unit; 
                }
            
                ItemLog::create([
                    'item_id' => $item['product_id'],
                    'amount' => $amount,
                    'unit' => $_item->unit,
                    'record_type' => $data->record_type == 'SALES' ? 'OUT' : 'IN',
                    'description' => $description,
                ]);
            }

        }

        $data->profile()->sync([$request->profile_id]);
    }
    
    public static function afterUpdate(Request $request, $data)
    {
        $data->items()->delete();
        $data->profile()->sync([$request->profile_id]);

        foreach($request->items as $item)
        {
            $data->items()->create($item);

            if($data->record_status == 'PUBLISH')
            {

                $_item = Item::where('id', $item['product_id'])->with('conversions')->first();
                $amount = $item['qty'];
                $description = $data->record_type.' #' . $data->code;
                if($_item->unit != $item['unit'])
                {
                    $conversion = $_item->conversions->where('unit', $item['unit'])->first();
                    $amount = $amount * $conversion->value;
                    $description .= ' - conversion from '. $item['qty'] . ' ' . $item['unit'] . ' to ' . $amount .' '.$_item->unit; 
                }

                ItemLog::create([
                    'item_id' => $item['product_id'],
                    'amount' => $amount,
                    'unit' => $_item->unit,
                    'record_type' => $data->record_type == 'SALES' ? 'OUT' : 'IN',
                    'description' => $description,
                ]);
            }
        }
    }
}
