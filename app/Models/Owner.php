<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'address', 'email', 'telephone'];

    protected $hidden = ['created_at', 'updated_at'];

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
