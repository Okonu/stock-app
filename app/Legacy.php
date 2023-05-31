<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Legacy extends Model
{
    protected $table = 'legacies';

    protected $fillable = ['garden', 'invoice', 'qty', 'grade', 'package'];

    protected $hidden = ['created_at', 'updated_at'];
}
