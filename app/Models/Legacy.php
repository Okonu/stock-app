<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Legacy extends Model
{
    use HasFactory;
    
    protected $table = 'legacies';

    protected $fillable = ['garden', 'invoice', 'qty', 'grade', 'package', 'mismatch', 'comment'];

    protected $hidden = ['created_at', 'updated_at'];
}
