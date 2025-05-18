<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    //
    use HasCreator, HasActivity;

    protected $table = 'sp_payment_methods';
    protected $guarded = ['id'];
}