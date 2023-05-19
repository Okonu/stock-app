<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Legacy extends Model
{
    protected $fillable = ['garden', 'grade', 'invoice', 'qty'];

    protected $hidden = ['created_at', 'updated_at'];
}
