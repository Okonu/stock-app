<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Garden extends Model
{
    protected $fillable = ['owner_id', 'name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
