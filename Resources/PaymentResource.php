<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Libraries\Components\Button;
use App\Libraries\Components\Delete;
use App\Modules\SalesPurchases\Models\Invoice;
use App\Modules\SalesPurchases\Models\Payment;
use App\Modules\SalesPurchases\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentResource extends Resource
{

    protected static ?string $navigationGroup = 'Sales & Purchases';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?string $navigationIcon = 'bx bx-dollar';
    protected static ?string $slug = 'sales-purchases/payments';
    protected static ?string $routeGroup = 'sales-purchases';

    protected static $model = Payment::class;

    public static function table()
    {
        return [
            'invoice.code' => [
                'label' => 'Invoice',
                '_searchable' => true
            ],
            'payment_method.name' => [
                'label' => 'Payment Method',
                '_searchable' => true
            ],
            'amount' => [
                'label' => 'Amount',
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
            'created_at' => [
                'label' => 'Date',
                '_searchable' => false
            ],
            '_action'
        ];
    }

    public static function form()
    {
        $Invoices = Invoice::whereDoesntHave('payment')->get();
        $selectedInvoices = [];
        foreach ($Invoices as $invoice) {
            $selectedInvoices[$invoice->id] = $invoice->code;
        }

        if(static::$record)
        {
            $selectedInvoices[static::$record->invoice_id] = static::$record->invoice->code;
        }

        $PaymentMethod = PaymentMethod::get();
        $selectedPaymentMethod = [];
        foreach ($PaymentMethod as $paymentMethod) {
            $selectedPaymentMethod[$paymentMethod->id] = $paymentMethod->name;
        }

        return [
            'Basic Information' => [
                'invoice_id' => [
                    'label' => 'Invoice',
                    'type' => 'select',
                    'options' => $selectedInvoices,
                    'placeholder' => 'Choose Invoice',
                    'required' => true,
                ],
                'payment_method_id' => [
                    'label' => 'Payment Method',
                    'type' => 'select',
                    'options' => $selectedPaymentMethod,
                    'placeholder' => 'Choose Payment Method',
                    'required' => true,
                ],
                'amount' => [
                    'label' => 'Amount',
                    'type' => 'tel',
                    'placeholder' => 'Enter amount'
                ],
                'record_status' => [
                    'label' => 'Status',
                    'type' => 'select',
                    'options' => [
                        'DRAFT' => 'DRAFT',
                        'PUBLISH' => 'PUBLISH'
                    ],
                    'required' => true
                ],
                'reference' => [
                    'label' => 'Reference',
                    'type' => 'text',
                    'placeholder' => 'Enter Reference',
                ],
            ],
        ];
    }

    public static function beforeCreate(Request $request)
    {
        $invoice = Invoice::where('id', $request->invoice_id)->first();
        if($invoice->record_type == 'PURCHASES')
        {
            $request->merge(['record_type' => 'OUT']);
        }
    }
    
    public static function beforeUpdate(Request $request, $data)
    {
        $invoice = Invoice::where('id', $request->invoice_id)->first();
        if($invoice->record_type == 'PURCHASES')
        {
            $request->merge(['record_type' => 'OUT']);
        }
    }

    public static function detail()
    {
        return [
            'Basic Information' => [
                'invoice.code' => 'Invoice',
                'payment_method.name' => 'Payment Method',
                'amount' => 'Amount',
                'record_type' => 'Record Type',
                'record_status' => 'Record Status',
                'reference' => 'Reference',
            ],
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
}
