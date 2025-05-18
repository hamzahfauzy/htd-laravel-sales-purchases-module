<?php

namespace App\Modules\SalesPurchases\Resources\Report;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class SalesReportResource extends Resource {

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Sales';
    protected static ?string $navigationIcon = 'bx bx-file';
    protected static ?string $slug = 'reports/sales';
    protected static ?string $routeGroup = 'reports';
    protected static $deleteRoute = false;
    public static $dataTableClass = 'sales-datatable';

    protected static $model = InvoiceItem::class;

    public static function mount()
    {
        static::addScripts([
            asset('modules/salespurchases/js/sales-report-resource.js')
        ]);
    }

    public static function getModel()
    {
        $date_start = request('filter.date_start', date('Y-m-d'));
        $date_end = request('filter.date_end', date('Y-m-d'));
        $model = static::$model::select(
                            'inv_items.name as product_name',
                            DB::raw("COALESCE(SUM(sp_invoice_items.qty),0) AS total_qty"),
                            DB::raw("COALESCE(SUM(sp_invoice_items.total_discount),0) AS total_discount"),
                            DB::raw("COALESCE(SUM(sp_invoice_items.final_price),0) AS total_price"),
                        )
                        ->join('sp_invoices','sp_invoices.id','=','sp_invoice_items.invoice_id')
                        ->join('inv_items','inv_items.id','=','sp_invoice_items.product_id')
                        ->groupBy('sp_invoice_items.product_id')
                        ->where('sp_invoices.record_status','PUBLISH')
                        ->where('sp_invoices.record_type','SALES');

        if(isset($_GET['date']))
        {
            $model = $model->whereBetween('sp_invoices.created_at', '<=', $_GET['date'].' 23:59:59');
        }

        return $model;
    }

    public static function getPages()
    {
        $resource = static::class;
        return [
            'index' => new \App\Libraries\Abstract\Pages\ListPage($resource),
        ];
    }

    public static function table()
    {
        return [
            'product_name' => [
                'label' => 'Name',
                '_searchable' => [
                    'inv_items.name',
                ],
                '_order' => 'product_name'
            ],
            'unit' => [
                'label' => 'Unit',
                '_searchable' => false,
            ],
            'total_qty' => [
                'label' => 'Total Qty',
                '_searchable' => false,
                '_order' => false
            ],
            'total_discount' => [
                'label' => 'Total Discount',
                '_searchable' => false,
                '_order' => false
            ],
            'total_price' => [
                'label' => 'Total Price',
                '_searchable' => false,
                '_order' => false
            ],
        ];
    }

    public static function listHeader()
    {
        return [
            'title' => 'Sales Report',
            'button' => [
                '<button class="btn btn-primary btn-sm filter-btn" type="button" data-bs-toggle="modal" data-bs-target="#filterModal">Filter</button>
                <!-- Modal -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="filterModalLabel">Filter</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label>Date Start</label>
                                <input type="date" class="form-control" id="date_start" name="date_start" value="'.date('Y-m-d').'">
                            </div>
                            <div class="form-group">
                                <label>Date End</label>
                                <input type="date" class="form-control" id="date_end" name="date_end" value="'.date('Y-m-d').'">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary btn-filter">Submit</button>
                        </div>
                    </div>
                </div>
                </div>'
            ]
        ];
    }
}