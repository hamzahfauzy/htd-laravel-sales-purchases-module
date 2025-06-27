<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
use App\Modules\Inventory\Models\Item;
use App\Traits\HasDotNotationFilter;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //
    use HasCreator, HasActivity, HasDotNotationFilter;
    protected $table = 'sp_prices';
    protected $guarded = ['id'];

    protected static function booted()
    {
        // for later
        static::retrieved(function ($model) {
            if(request()->routeIs('sales-purchases.sales-purchases/prices.index') || request()->routeIs('sales-purchases.sales-purchases/prices.detail') || request()->routeIs('products.datatable'))
            {
                $model->purchase_price = number_format($model->purchase_price);
                $model->amount_1 = number_format($model->amount_1);
                $model->min_qty_1 = number_format($model->min_qty_1);
                $model->amount_2 = number_format($model->amount_2);
                $model->min_qty_2 = number_format($model->min_qty_2);
                $model->amount_3 = number_format($model->amount_3);
                $model->min_qty_3 = number_format($model->min_qty_3);
                $model->amount_4 = number_format($model->amount_4);
                $model->min_qty_4 = number_format($model->min_qty_4);
                $model->amount_5 = number_format($model->amount_5);
                $model->min_qty_5 = number_format($model->min_qty_5);
            }
        });
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }
    
    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id', 'id');
    }
}