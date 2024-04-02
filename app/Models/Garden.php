<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Garden extends Model
{
    use HasFactory;
    
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
