<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobSkill extends Model
{
    protected $table = 'job_skills';

    protected $fillable = [
        'job_id',
        'skill_id',
    ];

    public $timestamps = false;

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}

