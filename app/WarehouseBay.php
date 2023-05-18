<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarehouseBay extends Model
{
    protected $table = 'warehouse_bays';

    protected $fillable = ['warehouse_id', 'name'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
