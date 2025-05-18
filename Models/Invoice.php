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

    function items()
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id', 'id');
    }

    function payment()
    {
        return $this->hasMany(Payment::class, 'invoice_id', 'id');
    }
}
