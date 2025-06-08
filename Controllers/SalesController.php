<?php

namespace App\Modules\SalesPurchases\Controllers;

use App\Modules\Inventory\Models\Item;
use App\Modules\Inventory\Models\ItemLog;
use App\Modules\SalesPurchases\Models\Invoice;
use App\Modules\SalesPurchases\Models\Price;
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

        ItemLog::create([
            'item_id' => $item->id,
            'unit' => $request->unit,
            'amount' => $request->stock,
            'record_type' => 'IN',
            'description' => 'Add via pos quick form'
        ]);

        Price::create([
            'product_id' => $item->id,
            'unit' => $request->unit,
            'amount_1' => $request->price,
            'min_qty_1' => 1
        ]);

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
}