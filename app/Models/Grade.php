<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}
