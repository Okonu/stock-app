<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = ['name', 'address', 'email', 'telephone'];

    protected $hidden = ['created_at', 'updated_at'];

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
