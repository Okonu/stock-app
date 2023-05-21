<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bay extends Model
{
    protected $fillable = ['warehouse_id', 'name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}