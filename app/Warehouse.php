<?php

namespace App;
use App\Stock;


use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = ['name'];

    public function bays()
    {
        return $this->hasMany(WarehouseBay::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }
}
