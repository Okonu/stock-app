<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['name'];

    public function bays()
    {
        return $this->hasMany(WarehouseBay::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
