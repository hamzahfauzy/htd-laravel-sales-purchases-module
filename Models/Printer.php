<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasCreator;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    //
    use HasCreator;
    protected $table = 'sp_printers';
    protected $guarded = ['id'];
}