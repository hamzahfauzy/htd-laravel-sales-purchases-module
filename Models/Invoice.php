<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Models\Profile;
use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    use HasCreator, HasActivity;

    protected $table = 'sp_invoices';
    protected $guarded = ['id'];

    protected static function booted()
    {
        // for later
        static::retrieved(function ($model) {
            $model->total_price = number_format($model->total_price);
            $model->final_price = number_format($model->final_price);
            $model->invoice_discount = number_format($model->invoice_discount);
            $model->total_discount = number_format($model->total_discount);
            $model->total_qty = number_format($model->total_qty);
        });
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    function payment()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }

    function profile()
    {
        return $this->belongsToMany(Profile::class, 'sp_profile_invoices', 'profile_id', 'invoice_id');
    }

}
