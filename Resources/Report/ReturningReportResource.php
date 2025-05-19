<?php

namespace App\Modules\SalesPurchases\Resources\Report;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class ReturningReportResource extends Resource {

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Returning Report';
    protected static ?string $navigationIcon = 'bx bx-file';
    protected static ?string $slug = 'reports/returning';
    protected static ?string $routeGroup = 'reports';
    protected static $deleteRoute = false;
    public static $dataTableClass = 'discount-datatable';

    protected static $model = InvoiceItem::class;

    public static function mount()
    {
        static::addScripts([
            asset('modules/salespurchases/js/discount-report-resource.js')
        ]);
    }

    public static function getModel()
    {
        $date_start = request('filter.date_start', date('Y-m-d')) . ' 00:00:00';
        $date_end = request('filter.date_end', date('Y-m-d')) . ' 23:59:59';
        $model = static::$model::select(
                            DB::raw('DATE_FORMAT(sp_invoice_items.updated_at, "%Y-%m-%d") date'),
                            DB::raw('FORMAT(COALESCE(SUM(sp_invoice_items.final_price),0),0) return_total'),
                        )
                        ->groupBy(DB::raw('DATE_FORMAT(sp_invoice_items.updated_at, "%Y-%m-%d")'))
                        ->where('sp_invoice_items.record_status','RETURN')
                        ->whereBetween('sp_invoice_items.updated_at',[$date_start, $date_end]);

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
            'date' => [
                'label' => 'Date',
                '_searchable' => [
                    'sp_invoice_items.created_at',
                ],
                '_order' => 'date'
            ],
            'return_total' => [
                'label' => 'Total Returning',
                '_searchable' => false,
                '_order' => 'return_total'
            ],
        ];
    }

    public static function listHeader()
    {
        return [
            'title' => 'Returning Report',
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