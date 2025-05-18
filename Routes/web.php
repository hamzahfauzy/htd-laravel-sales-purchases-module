<?php

use App\Modules\SalesPurchases\Models\Printer;
use Illuminate\Support\Facades\Route;

Route::get('pos', function () {
    return view('sales-purchases::pos');
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
