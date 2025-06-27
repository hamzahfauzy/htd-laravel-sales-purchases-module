<?php

namespace App\Modules\SalesPurchases\Controllers;

use App\Modules\Inventory\Models\Item;
use App\Modules\Inventory\Models\ItemLog;
use App\Modules\SalesPurchases\Models\Invoice;
use App\Modules\SalesPurchases\Models\Price;
use App\Modules\SalesPurchases\Models\Product;
use Illuminate\Http\Request;

class SalesController
{
    function addProduct(Request $request)
    {
        $item = Item::create([
            'code' => $request->code,
            'name' => $request->name,
            'unit' => $request->unit,
            'low_stock_alert' => 20,
        ]);

        Price::create([
            'product_id' => $item->id,
            'unit' => $request->unit,
            'purchase_price' => $request->purchase_price,
            'amount_1' => $request->price_1,
            'min_qty_1' => $request->qty_1,
            'amount_2' => $request->price_2,
            'min_qty_2' => $request->qty_2,
            'amount_3' => $request->price_3,
            'min_qty_3' => $request->qty_3,
            'amount_4' => $request->price_4,
            'min_qty_4' => $request->qty_4,
            // 'amount_5' => $request->price_5,
            // 'min_qty_5' => $request->qty_5,
        ]);

        if($request->stok)
        {
            ItemLog::create([
                'item_id' => $item->id,
                'unit' => $request->unit,
                'amount' => $request->stock,
                'record_type' => 'IN',
                'description' => 'Add via pos quick form'
            ]);
            
            $invoice = Invoice::create([
                "code" => 'INV-'.strtotime('now').'-'.rand(11111,99999),
                "total_item" => 1,
                "total_price" => $request->stock_price,
                "total_qty" => $request->stock,
                "final_price" => $request->stock_price,
                "invoice_discount" => 0,
                "total_discount" => 0,
                "record_status" => "PUBLISH",
                "record_type" => 'PURCHASE',
            ]);
    
            $invoice->items()->createMany([
                [
                    'product_id' => $item->id,
                    'name' => $item->name,
                    'qty' => $request->stock,
                    'unit' => $request->unit,
                    'base_price' => $request->stock_price/$request->stock,
                    'total_price' => $request->stock_price,
                    'final_price' => $request->stock_price
                ]
            ]);
    
            $invoice->payment()->create([
                'payment_method_id' => 1,
                'amount' => $request->stock_price,
                'change' => 0,
                "record_status" => "PUBLISH",
                'reference' => 'transaction from quick add form'
            ]);
        }

        return [
            'status' => 'success',
            'message' => 'Add product success'
        ];
    }
    function voidSales(Request $request)
    {
        $invoice = Invoice::where('code', $request->code)->where('record_type','SALES')->where('record_status','PUBLISH')->first();
        if(!$invoice)
        {
            return [
                'status' => 'fail',
                'message' => 'Transaction not found'
            ];
        }
        
        $invoice->payment()->update([
            'record_status' => 'VOID'
        ]);

        $invoice->update([
            'record_status' => 'VOID'
        ]);
        

        // cancel item log
        foreach($invoice->items as $item)
        {

            $_item = Item::where('id', $item->product_id)->with('conversions')->first();
            $amount = $item->qty;
            $description = 'VOID SALES #' . $invoice->code;
            if($_item->unit != $item->unit)
            {
                $conversion = $_item->conversions->where('unit', $item->unit)->first();
                $amount = $amount * $conversion->value;
                $description .= ' - conversion from '. $item->qty . ' ' . $item->unit . ' to ' . $amount .' '.$_item->unit; 
            }

            ItemLog::create([
                'item_id' => $item->product_id,
                'amount' => $amount,
                'unit' => $_item->unit,
                'record_type' => 'IN',
                'description' => $description,
            ]);
        }

        return [
            'status' => 'success',
            'message' => 'Transaction void success'
        ];
    }

    function returnSales(Request $request)
    {
        $invoice = Invoice::with('items.product','payment')->where('code', $request->code)->where('record_type','SALES')->where('record_status','PUBLISH')->first();
        if(!$invoice)
        {
            return [
                'status' => 'fail',
                'message' => 'Transaction not found'
            ];
        }

        return [
            'status' => 'success',
            'data' => $invoice
        ];
    }

    function getProduct($code)
    {
        $product = Product::with('prices')->where('code', $code)->first();

        return [
            'status' => 'success',
            'data' => $product
        ];
    }

    function updateProduct(Request $request)
    {
        $product = Product::where('code', $request->code)->first();
        $product->update([
            'name' => $request->name,
            'unit' => $request->unit,
        ]);

        Price::updateOrCreate([
            'product_id' => $product->id,
            'unit' => $request->unit,
        ],[
            'product_id' => $product->id,
            'unit' => $request->unit,
            'purchase_price' => $request->purchase_price,
            'amount_1' => $request->price_1,
            'min_qty_1' => $request->qty_1,
            'amount_2' => $request->price_2,
            'min_qty_2' => $request->qty_2,
            'amount_3' => $request->price_3,
            'min_qty_3' => $request->qty_3,
            'amount_4' => $request->price_4,
            'min_qty_4' => $request->qty_4,
            // 'amount_5' => $request->price_5,
            // 'min_qty_5' => $request->qty_5,
        ]);

        if($request->stok)
        {
            ItemLog::create([
                'item_id' => $product->id,
                'unit' => $request->unit,
                'amount' => $request->stock,
                'record_type' => 'IN',
                'description' => 'Add via pos quick form'
            ]);
            
            $invoice = Invoice::create([
                "code" => 'INV-'.strtotime('now').'-'.rand(11111,99999),
                "total_item" => 1,
                "total_price" => $request->stock_price,
                "total_qty" => $request->stock,
                "final_price" => $request->stock_price,
                "invoice_discount" => 0,
                "total_discount" => 0,
                "record_status" => "PUBLISH",
                "record_type" => 'PURCHASE',
            ]);
    
            $invoice->items()->createMany([
                [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'qty' => $request->stock,
                    'unit' => $request->unit,
                    'base_price' => $request->stock_price/$request->stock,
                    'total_price' => $request->stock_price,
                    'final_price' => $request->stock_price
                ]
            ]);
    
            $invoice->payment()->create([
                'payment_method_id' => 1,
                'amount' => $request->stock_price,
                'change' => 0,
                "record_status" => "PUBLISH",
                'reference' => 'transaction from quick add form'
            ]);
        }

        return [
            'status' => 'success',
            'data' => $product
        ];
    }
}