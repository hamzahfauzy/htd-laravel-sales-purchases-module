<?php

namespace App\Modules\SalesPurchases\Models;

use App\Modules\Base\Traits\HasActivity;
use App\Modules\Base\Traits\HasCreator;
use App\Modules\Inventory\Models\Item;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    //
    use HasCreator, HasActivity;
    protected $table = 'sp_prices';
    protected $guarded = ['id'];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id', 'id');
    }
}