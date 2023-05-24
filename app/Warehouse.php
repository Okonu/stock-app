<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['name'];

    // public function stocks()
    // {
    //     return $this->hasMany(Stock::class);
    // }

    public function bays()
    {
        return $this->hasMany(WarehouseBay::class);
    }
}
