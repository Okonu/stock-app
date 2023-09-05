<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Legacy extends Model
{
    protected $table = 'legacies';

<<<<<<< HEAD
    protected $fillable = ['garden', 'invoice', 'qty', 'grade', 'package', 'mismatch', 'comment'];
=======
    protected $fillable = ['garden', 'invoice', 'qty', 'grade', 'package'];
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183

    protected $hidden = ['created_at', 'updated_at'];
}
