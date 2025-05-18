<?php

use App\Modules\Inventory\Models\Item;
use App\Modules\Inventory\Models\ItemLog;
use App\Modules\SalesPurchases\Models\Invoice;
use App\Modules\SalesPurchases\Models\PaymentMethod;
use App\Modules\SalesPurchases\Models\Price;
use App\Modules\SalesPurchases\Models\Printer;
use Illuminate\Support\Facades\Route;

Route::get('pos', function () {
    if (isset($_GET['code'])) {
        $item = Item::where('code', $_GET['code'])->first();

        $price = Price::where('product_id', $item->id)->where('unit', $item->unit)->first();
        $units = Price::where('product_id', $item->id)->get();

        $item->price = $price->amount_1;

        $item->units = $units;

        return response()->json($item);
    }

    $paymentMethods = PaymentMethod::get();

    return view('sales-purchases::pos', compact('paymentMethods'));
});

Route::post('pos', function () {

    try {

        $invoice = Invoice::create([
            "code" => "INV-" . date('YmdHis'),
            "total_item" => request()['total_item'],
            "total_price" => request()['total_price'],
            "total_qty" => request()['total_qty'],
            "final_price" => request()['final_price'],
            "total_discount" => request()['discount'],
            "record_status" => "PUBLISH",
        ]);

        $invoice->items()->createMany(request()['items']);

        $invoice->payment()->create([
            'payment_method_id' => request()['payment_method'],
            'amount' => request()['payment_amount'],
            'change' => request()['change'],
            "record_status" => "PUBLISH",
        ]);

        foreach (request()['items'] as $item) {
            ItemLog::create([
                'item_id' => $item['product_id'],
                'amount' => $item['qty'],
                'unit' => $item['unit'],
                'record_type' => 'OUT',
                'description' => 'Sales #' . $invoice->code,
            ]);
        }

        Printer::first()->printStruk([
            'toko' => [
                'nama' => env('STORE_NAME', 'TOKO MAJU JAYA'),
                'alamat' => env('STORE_ADDRESS', 'Jl. Mawar No. 123, Jakarta'),
                'telepon' => env('STORE_PHONE', '0812-3456-7890'),
            ],
            'kasir' => auth()->user()->name,
            'tanggal' => date('Y-m-d H:i:s'),
            'items' => request()['items'],
            'bayar' => request()['payment_amount'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Transaksi berhasil',
            'data' => $invoice
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Transaksi gagal',
            'error' => $e->getMessage()
        ]);
    }
});

Route::get('print-tes', function () {
    $transaksi = [
        'toko' => [
            'nama' => 'TOKO MAJU JAYA',
            'alamat' => 'Jl. Mawar No. 123, Jakarta',
            'telepon' => '0812-3456-7890',
        ],
        'kasir' => 'Budi',
        'tanggal' => date('Y-m-d H:i:s'),
        'items' => [
            ['nama' => 'Indomie Goreng', 'qty' => 2, 'harga' => 3500],
            ['nama' => 'Teh Botol', 'qty' => 1, 'harga' => 4000],
            ['nama' => 'Kopi Susu Sachet', 'qty' => 3, 'harga' => 2000],
        ],
        'bayar' => 20000
    ];

    Printer::find(1)->printStruk($transaksi);
});
