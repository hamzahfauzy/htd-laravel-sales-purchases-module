<?php

namespace App\Modules\SalesPurchases\Resources;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\Invoice;
use App\Modules\SalesPurchases\Models\Payment;
use App\Modules\SalesPurchases\Models\PaymentMethod;

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
            '_action'
        ];
    }

    public static function form()
    {
        $Invoices = Invoice::get();
        $selectedInvoices = [];
        foreach ($Invoices as $invoice) {
            $selectedInvoices[$invoice->id] = $invoice->code;
        }

        $PatmentMethod = PaymentMethod::get();
        $selectedPatmentMethod = [];
        foreach ($PatmentMethod as $paymentMethod) {
            $selectedPatmentMethod[$paymentMethod->id] = $paymentMethod->name;
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
                    'options' => $selectedPatmentMethod,
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

    public static function detail()
    {
        return [
            'Basic Information' => [
                'invoice.code' => 'Invoice',
                'payment_method.name' => 'Payment Method',
                'amount' => 'Amount',
                'record_type' => 'Record Type',
                'record_status' => 'Record Status',
            ],
        ];
    }
}
