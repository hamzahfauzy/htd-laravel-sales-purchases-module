<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
use App\Traits\HasDotNotationFilter;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    use HasCreator, HasDotNotationFilter, HasActivity;

    protected $table = 'sp_payments';
    protected $guarded = ['id'];

    protected static function booted()
    {
        // for later
        static::retrieved(function ($model) {
            $model->amount = number_format($model->amount);
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

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
}
