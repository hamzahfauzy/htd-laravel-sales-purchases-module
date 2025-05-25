<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Models\Profile;
use Illuminate\Database\Eloquent\Model;

class ProfileInvoice extends Model
{
    //
    protected $table = 'sp_profile_invoices';
    protected $guarded = ['id'];

    function invoices()
    {
        return $this->hasMany(Invoice::class, 'id', 'invoice_id');
    }

    function profile()
    {
        return $this->belongsTo(Profile::class, 'id', 'profile_id');
    }
}