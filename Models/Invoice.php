<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
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
        // static::retrieved(function ($model) {
        //     $model->total_price = number_format($model->total_price);
        //     $model->final_price = number_format($model->final_price);
        //     $model->total_discount = number_format($model->total_discount);
        //     $model->total_qty = number_format($model->total_qty);
        // });
    }

    function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    function payment()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }

}
