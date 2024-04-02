<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Warehouse extends Model
{
    use HasFactory;
    
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
