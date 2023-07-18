<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = ['skill'];

    public function seekers()
    {
        return $this->belongsToMany(Seeker::class);
    }

    public function seekerProfiles()
    {
        return $this->belongsToMany(SeekerProfile::class, 'seeker_skills', 'skill_id', 'seeker_id');
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_skills', 'skill_id', 'job_id');
    }
}

