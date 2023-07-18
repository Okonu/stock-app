<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $fillable = ['seeker_id', 'job_id', 'status'];

    public function seeker()
    {
        return $this->belongsTo(Seeker::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
