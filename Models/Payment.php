<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    use HasCreator;

    protected $table = 'sp_payments';
    protected $guarded = ['id'];
}