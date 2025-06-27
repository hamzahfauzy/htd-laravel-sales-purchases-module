<?php

use App\Modules\Base\Models\Profile;
use App\Modules\Inventory\Models\Item;
use App\Modules\Inventory\Models\ItemConversion;
use App\Modules\Inventory\Models\ItemLog;
use App\Modules\SalesPurchases\Models\Invoice;
use App\Modules\SalesPurchases\Models\PaymentMethod;
use App\Modules\SalesPurchases\Models\Price;
use App\Modules\SalesPurchases\Models\Printer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;

Route::middleware(['auth', 'web', 'verified'])->group(function () {

    Route::prefix('sales-purchases')->group(function(){
        Route::get('products', [\App\Modules\SalesPurchases\Controllers\ProductController::class,'get'])->name('products.get');
        Route::get('products/datatable', [\App\Modules\SalesPurchases\Controllers\ProductController::class,'datatable'])->name('products.datatable');
        Route::get('customers', function(){
            $term = request('term', '');
            $items = Profile::where(function($query) use ($term){
                                    $query->where('name','LIKE', "%$term%")
                                    ->orWhere('code','LIKE', "%$term%");
                                })
                                ->where('record_type','CUSTOMER')
                                ->limit(20)
                                ->get();

            return $items;
        })->name('customers.get');
        Route::post('void-sales', [\App\Modules\SalesPurchases\Controllers\SalesController::class,'voidSales'])->name('sales.void');
        Route::post('add-product', [\App\Modules\SalesPurchases\Controllers\SalesController::class,'addProduct'])->name('sales.add-product');
        Route::post('update-product', [\App\Modules\SalesPurchases\Controllers\SalesController::class,'updateProduct'])->name('sales.add-product');
        Route::get('get-product/{code}', [\App\Modules\SalesPurchases\Controllers\SalesController::class,'getProduct'])->name('sales.add-product');
        Route::post('return-sales', [\App\Modules\SalesPurchases\Controllers\SalesController::class,'returnSales'])->name('sales.return');
    });

    Route::get('import', function () {

        $inputFileName = public_path('modules/salespurchases/dataproducts.xlsx');
        try {
            $spreadsheet = IOFactory::load($inputFileName);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();
            foreach ($data as $i => $row) {
                if ($i == 0) continue;
                $code = $row[0]; 
                $name = $row[1];
                $unit = empty($row[2]) ? 'PCS' : $row[2];
                $unit2 = $row[3];
                $conversion2 = $row[4];
                $unit3 = $row[5];
                $conversion3 = $row[6];
                $unit4 = $row[7];
                $conversion4 = $row[8];
                $unit5 = $row[9];
                $conversion5 = $row[10];
                // $unit6 = $row[11];
                // $conversion6 = $row[12];
                // $unit7 = $row[13];
                // $conversion7 = $row[14];
                // $unit8 = $row[15];
                // $conversion8 = $row[16];
                // $unit9 = $row[17];
                // $conversion9 = $row[18];
                // $unit10 = $row[19];
                // $conversion10 = $row[20];
                $purchase_price = $row[21];
                $amount1 = $row[23];
                $qty1 = $row[24];
                $amount2 = $row[25];
                $qty2 = $row[26];
                $amount3 = $row[27];
                $qty3 = $row[28];
                $low_stock_alert = 20;

                if(empty($name)) continue;

                if(Item::where('code', $code)->exists()) continue;

                $itemModel = Item::create([
                    'code' => $code,
                    'name' => $name,
                    'unit' => $unit,
                    'low_stock_alert' => $low_stock_alert,
                ]);

                if($unit2 && $unit2 != 0 && $unit2 != $unit)
                {
                    ItemConversion::create([
                        'item_id' => $itemModel->id,
                        'unit' => $unit2,
                        'value' => $conversion2
                    ]);
                }

                if($unit3 && $unit3 != 0 && $unit3 != $unit)
                {
                    ItemConversion::create([
                        'item_id' => $itemModel->id,
                        'unit' => $unit3,
                        'value' => $conversion3
                    ]);
                }
                
                if($unit4 && $unit4 != 0 && $unit4 != $unit)
                {
                    ItemConversion::create([
                        'item_id' => $itemModel->id,
                        'unit' => $unit4,
                        'value' => $conversion4
                    ]);
                }
                
                if($unit5 && $unit5 != 0 && $unit5 != $unit)
                {
                    ItemConversion::create([
                        'item_id' => $itemModel->id,
                        'unit' => $unit5,
                        'value' => $conversion5
                    ]);
                }

                Price::create([
                    'product_id' => $itemModel->id,
                    'unit' => $unit,
                    'purchase_price' => $purchase_price,
                    'amount_1' => $amount1,
                    'min_qty_1' => $qty1,
                    'amount_2' => $amount2,
                    'min_qty_2' => $qty2,
                    'amount_3' => $amount3,
                    'min_qty_3' => $qty3,
                ]);
            }
            
            return "Successfully imported " . count($data)-1 . " items";
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

    Route::post('pos/print-last-invoice', function (Request $request) {
        $invoice = Invoice::latest()->first();
        Printer::first()?->printStruk([
            'toko' => [
                'nama' => env('STORE_NAME', 'TOKO MAJU JAYA'),
                'alamat' => env('STORE_ADDRESS', 'Jl. Mawar No. 123, Jakarta'),
                'telepon' => env('STORE_PHONE', '0812-3456-7890'),
            ],
            'kasir' => auth()->user()->name,
            'member' => $invoice->profile&&isset($invoice->profile[0])?$invoice->profile[0]->name:'Walkin Guest',
            'code' => $invoice->code,
            'tanggal' => date('Y-m-d H:i:s'),
            'items' => request()['items'],
            'bayar' => request()['payment_amount'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Invoice Print',
            'data' => $invoice
        ]);
    });
    Route::post('pos', function (Request $request) {

        if($request->code)
        {
            // update
            $invoice = Invoice::where('code', $request->code)->first();
            $invoice->update([
                "total_item" => $request->total_item,
                "total_price" => $request->total_price,
                "total_qty" => $request->total_qty,
                "final_price" => $request->final_price,
                "invoice_discount" => $request->discount,
                "total_discount" => $request->discount,
            ]);

            $oldItems = $invoice->items()->get()->keyBy('product_id');
            $incomingItems = collect($request->items);

            // Ambil ID dari item yang dikirim
            $receivedIds = $incomingItems->pluck('product_id')->filter()->all();

            foreach ($incomingItems as $item) {
                // Update hanya jika qty berubah
                if ($oldItems->has($item['product_id'])) {
                    $old = $oldItems[$item['product_id']];
                    if ($old->qty != $item['qty']) {
                        $margin = $old->qty - $item['qty'];
                        $old->update($item);
                        
                        // selisih
                        ItemLog::create([
                            'item_id' => $item['product_id'],
                            'amount' => $margin,
                            'unit' => $item['unit'],
                            'record_type' => 'IN',
                            'description' => 'RETURN ITEM FROM SALES #'.$request->code,
                        ]);
                    }
                }
            }

            $deletedItems = $oldItems->whereNotIn('product_id', $receivedIds);
            foreach ($deletedItems as $item) {
                ItemLog::create([
                    'item_id' => $item->product_id,
                    'amount' => $item->qty,
                    'unit' => $item->unit,
                    'record_type' => 'IN',
                    'description' => 'RETURN ITEM FROM SALES #'.$request->code,
                ]);

                $item->delete();
            }

            Printer::first()?->printStruk([
                'toko' => [
                    'nama' => env('STORE_NAME', 'TOKO MAJU JAYA'),
                    'alamat' => env('STORE_ADDRESS', 'Jl. Mawar No. 123, Jakarta'),
                    'telepon' => env('STORE_PHONE', '0812-3456-7890'),
                ],
                'kasir' => auth()->user()->name,
                'member' => $invoice->profile&&isset($invoice->profile[0])?$invoice->profile[0]->name:'Walkin Guest',
                'code' => $invoice->code,
                'tanggal' => date('Y-m-d H:i:s'),
                'items' => request()['items'],
                'bayar' => request()['payment_amount'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Transaksi Update',
                'data' => $invoice
            ]);
        }
        else
        {
            try {
    
                $invoice = Invoice::create([
                    "code" => 'INV-'.strtotime('now').'-'.rand(11111,99999),
                    "total_item" => request()['total_item'],
                    "total_price" => request()['total_price'],
                    "total_qty" => request()['total_qty'],
                    "final_price" => request()['final_price'],
                    "invoice_discount" => request()['discount'],
                    "total_discount" => request()['discount'],
                    "record_status" => "PUBLISH",
                    "record_type" => request()['record_type'],
                ]);

                if(request('customer_id'))
                {
                    $customer_id = request('customer_id');
                    $invoice->profile()->sync([$customer_id]);
                }
    
                $invoice->items()->createMany(request()['items']);
    
                $invoice->payment()->create([
                    'payment_method_id' => request()['payment_method'],
                    'amount' => request()['payment_amount'],
                    'change' => request()['change'],
                    "record_status" => "PUBLISH",
                    'reference' => request('payment_reference')
                ]);
    
                foreach (request()['items'] as $item) {
                    $_item = Item::where('id', $item['product_id'])->with('conversions')->first();
                    $amount = $item['qty'];
                    $description = $invoice->record_type .' #' . $invoice->code;
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
                        'record_type' => $invoice->record_type == 'SALES' ? 'OUT' : 'IN',
                        'description' => $description,
                    ]);
                }
    
                if($invoice->record_type == 'SALES')
                {
                    Printer::first()?->printStruk([
                        'toko' => [
                            'nama' => env('STORE_NAME', 'TOKO MAJU JAYA'),
                            'alamat' => env('STORE_ADDRESS', 'Jl. Mawar No. 123, Jakarta'),
                            'telepon' => env('STORE_PHONE', '0812-3456-7890'),
                        ],
                        'kasir' => auth()->user()->name,
                        'member' => $invoice->profile && isset($invoice->profile[0])?$invoice->profile[0]->name:'Walkin Guest',
                        'code' => $invoice->code,
                        'tanggal' => date('Y-m-d H:i:s'),
                        'items' => request()['items'],
                        'bayar' => request()['payment_amount'],
                    ]);
                }
    
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
                ], 400);
            }
        }

    });

    Route::get('print-tes', function () {
        $transaksi = [
            'toko' => [
                'nama' => 'TOKO MAJU JAYA',
                'alamat' => 'Jl. Mawar No. 123, Jakarta',
                'telepon' => '0812-3456-7890',
            ],
            'code' => 'INV-123456789-9876',
            'kasir' => 'Budi',
            'member' => 'Walkin Guest',
            'tanggal' => date('Y-m-d H:i:s'),
            'items' => [
                ['name' => 'Indomie Goreng', 'qty' => 2, 'base_price' => 3500, 'total_price' => 7000],
                ['name' => 'Teh Botol', 'qty' => 1, 'base_price' => 4000, 'total_price' => 4000],
                ['name' => 'Kopi Susu Sachet', 'qty' => 3, 'base_price' => 2000, 'total_price' => 6000],
            ],
            'bayar' => 20000
        ];

        // $printString = Printer::find(1)?->printString($transaksi);

        // echo "<pre>$printString</pre>";

        Printer::find(1)->printStruk($transaksi);
    });
});
