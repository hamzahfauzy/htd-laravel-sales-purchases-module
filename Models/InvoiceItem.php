<?php

namespace App\Modules\SalesPurchases\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'sp_invoice_items';
    protected $guarded = ['id'];

    function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
