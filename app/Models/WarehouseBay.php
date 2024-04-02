<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WarehouseBay extends Model
{
    use HasFactory;
    protected $table = 'warehouse_bays';

    protected $fillable = ['warehouse_id', 'name'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
