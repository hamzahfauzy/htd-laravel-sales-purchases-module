<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
use App\Modules\Inventory\Models\Item;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //
    use HasCreator, HasActivity;
    protected $table = 'sp_prices';
    protected $guarded = ['id'];

    protected static function booted()
    {
        // for later
        static::retrieved(function ($model) {
            $model->purchase_price = number_format($model->purchase_price);
            $model->amount_1 = number_format($model->amount_1);
            $model->min_qty_1 = number_format($model->min_qty_1);
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