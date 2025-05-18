<?php

use App\Http\Middleware\AllowedRoute;
use App\Modules\Inventory\Models\Item;
use App\Modules\Inventory\Models\ItemLog;
use App\Modules\SalesPurchases\Models\Invoice;
use App\Modules\SalesPurchases\Models\PaymentMethod;
use App\Modules\SalesPurchases\Models\Price;
use App\Modules\SalesPurchases\Models\Printer;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;

Route::middleware(['auth', 'web', 'verified'])->group(function () {

    Route::get('import', function () {

        $inputFileName = public_path('products.xlsx');
        try {
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            $raw = [];
            foreach ($data as $i => $row) {
                if ($i == 0 || empty($row[1]) || empty($row[2]) || empty($row[4]) || empty($row[6])) continue;
                $raw['code'][] = $row[1];
                $raw['name'][] = $row[2];
                $raw['price'][] = $row[11];

                $raw['base_unit_code'][] = $row[4];
                $raw['base_unit_value'][] = $row[5];

                $raw['sec_units_code'][] = $row[6];
                $raw['sec_units_value'][] = $row[7];
            }

            $new = [];
            for ($i = 0; $i < count($raw['code']); $i++) {
                $new[] = [
                    'code' => $raw['code'][$i],
                    'name' => $raw['name'][$i],
                    'price' => str_replace(',00', '', str_replace('.', '', $raw['price'][$i])),
                    'units' => [
                        'base' => [
                            'code' => $raw['base_unit_code'][$i],
                            'value' => $raw['base_unit_value'][$i],
                        ],
                        'secondary' => [
                            'code' => $raw['sec_units_code'][$i],
                            'value' => $raw['sec_units_value'][$i],
                        ],
                    ],
                ];
            }

            foreach ($new as $item) {
                $itemModel = Item::create([
                    'code' => $item['code'],
                    'name' => $item['name'],
                    'unit' => $item['units']['base']['code'],
                ]);

                $itemModel->conversions()->create([
                    'unit' => $item['units']['secondary']['code'],
                    'value' => $item['units']['secondary']['value'],
                ]);

                Price::create([
                    'product_id' => $itemModel->id,
                    'unit' => $item['units']['base']['code'],
                    'amount_1' => $item['price'],
                ]);
            }

            return "Successfully imported " . count($new) . " items";
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            die('Error loading file: ' . $e->getMessage());
        }
    });

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
                ['name' => 'Indomie Goreng', 'qty' => 2, 'base_price' => 3500, 'total_price' => 7000],
                ['name' => 'Teh Botol', 'qty' => 1, 'base_price' => 4000, 'total_price' => 4000],
                ['name' => 'Kopi Susu Sachet', 'qty' => 3, 'base_price' => 2000, 'total_price' => 6000],
            ],
            'bayar' => 20000
        ];

        Printer::find(1)->printStruk($transaksi);
    });
});
