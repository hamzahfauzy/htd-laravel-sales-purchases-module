<?php

namespace App\Modules\SalesPurchases\Resources\Report;

use App\Libraries\Abstract\Resource;
use App\Modules\SalesPurchases\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;

class PaymentMethodReportResource extends Resource {

    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Payment Method Report';
    protected static ?string $navigationIcon = 'bx bx-file';
    protected static ?string $slug = 'reports/payment-methods';
    protected static ?string $routeGroup = 'reports';
    protected static $deleteRoute = false;
    public static $dataTableClass = 'discount-datatable';

    protected static $model = PaymentMethod::class;

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
                            'name',
                            DB::raw('DATE_FORMAT(sp_payments.created_at, "%Y-%m-%d") date'),
                            DB::raw('FORMAT(COALESCE(SUM(sp_payments.amount),0),0) payment_total'),
                        )
                        ->leftJoin('sp_payments', 'sp_payments.payment_method_id','=','sp_payment_methods.id')
                        ->groupBy(DB::raw('DATE_FORMAT(sp_payments.created_at, "%Y-%m-%d")'), 'name')
                        ->where('sp_payments.record_status','PUBLISH')
                        ->where('sp_payments.record_type','IN')
                        ->whereBetween('sp_payments.created_at',[$date_start, $date_end]);

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
                    'sp_payments.created_at',
                ],
                '_order' => 'date'
            ],
            'name' => [
                'label' => 'Method Name',
                '_searchable' => [
                    'sp_payment_methods.name',
                ],
                '_order' => 'name'
            ],
            'payment_total' => [
                'label' => 'Total Payment',
                '_searchable' => false,
                '_order' => 'payment_total'
            ],
        ];
    }

    public static function listHeader()
    {
        return [
            'title' => 'Payment Methods Report',
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