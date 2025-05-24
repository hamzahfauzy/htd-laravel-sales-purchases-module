<?php

namespace App\Modules\SalesPurchases\Services;

use Illuminate\Support\Facades\DB;

class DashboardService
{
    static function revenue()
    {
        $today = \Carbon\Carbon::today();
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth();

        $summary = DB::table('sp_invoices')
            ->selectRaw("
                SUM(CASE WHEN DATE(created_at) = ? THEN final_price ELSE 0 END) AS `Today Revenue`,
                COUNT(CASE WHEN DATE(created_at) = ? THEN 1 ELSE NULL END) AS `Today Transaction`,
                SUM(CASE WHEN created_at >= ? THEN final_price ELSE 0 END) AS `Month Revenue`,
                COUNT(CASE WHEN created_at >= ? THEN 1 ELSE NULL END) AS `Month Transaction`
            ", [
                $today->toDateString(),
                $today->toDateString(),
                $startOfMonth,
                $startOfMonth,
            ])
            ->where('record_status', 'PUBLISH')
            ->where('record_type', 'SALES')
            ->first();

        return view('sales-purchases::dashboard.revenue', compact('summary'));
    }

    static function topStatistic()
    {
        $topSales = DB::table('sp_invoice_items')
            ->join('inv_items', 'sp_invoice_items.product_id', '=', 'inv_items.id')
            ->join('sp_invoices', 'sp_invoices.id', '=', 'sp_invoice_items.invoice_id')
            ->select(
                'inv_items.name',
                DB::raw('SUM(sp_invoice_items.qty) as total_qty'),
                DB::raw('SUM(sp_invoice_items.final_price) as total_sales')
            )
            ->groupBy('sp_invoice_items.product_id', 'inv_items.name')
            ->orderByDesc('total_sales') // atau 'total_qty' untuk produk terjual terbanyak
            ->where('sp_invoices.record_status', 'PUBLISH')
            ->where('sp_invoices.record_type', 'SALES')
            ->limit(10)
            ->get();
        
        $topProducts = DB::table('sp_invoice_items')
            ->join('inv_items', 'sp_invoice_items.product_id', '=', 'inv_items.id')
            ->join('sp_invoices', 'sp_invoices.id', '=', 'sp_invoice_items.invoice_id')
            ->select(
                'inv_items.name',
                DB::raw('SUM(sp_invoice_items.qty) as total_qty'),
                DB::raw('SUM(sp_invoice_items.final_price) as total_sales')
            )
            ->groupBy('sp_invoice_items.product_id', 'inv_items.name')
            ->orderByDesc('total_qty') // atau 'total_qty' untuk produk terjual terbanyak
            ->where('sp_invoices.record_status', 'PUBLISH')
            ->where('sp_invoices.record_type', 'SALES')
            ->limit(10)
            ->get();

        $lowStockProducts = DB::table('inv_items')
            ->join('inv_item_logs', 'inv_items.id', '=', 'inv_item_logs.item_id')
            ->select('inv_items.id', 'inv_items.name', 'inv_items.low_stock_alert', DB::raw('
                SUM(CASE WHEN inv_item_logs.record_type = "IN" THEN inv_item_logs.amount ELSE 0 END) -
                SUM(CASE WHEN inv_item_logs.record_type = "OUT" THEN inv_item_logs.amount ELSE 0 END) AS stock
            '))
            ->groupBy('inv_items.id', 'inv_items.name')
            ->havingRaw('stock < inv_items.low_stock_alert')
            ->whereNotNull('low_stock_alert')
            ->orderBy('stock', 'asc')
            ->get();

        return view('sales-purchases::dashboard.top', compact('topProducts', 'topSales', 'lowStockProducts'));
    }
}