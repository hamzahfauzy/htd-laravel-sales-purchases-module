<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    //
    use HasCreator;
    
    protected $table = 'sp_invoices';
    protected $guarded = ['id'];
}