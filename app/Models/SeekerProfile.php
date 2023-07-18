<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeekerProfile extends Model
{
    protected $table = 'seeker_profiles';

    protected $fillable = [
        'date_of_birth',
        'id_number',
        'location_id',
        'highest_level_id',
        'school',
        'year_of_completion',
        'resume',
        'seeker_id',
    ];

    // public function location()
    // {
    //     return $this->belongsTo(Location::class, 'seeker_locations', 'seeker_id', 'location_id');
    // }
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'seeker_locations', 'seeker_id', 'location_id');
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'seeker_interests', 'seeker_id', 'interest_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'seeker_skills', 'seeker_id', 'skill_id');
    }

    public function highestLevel()
    {
        return $this->belongsTo(HighestLevel::class, 'highest_level_id');
    }

    public function seeker()
    {
        return $this->belongsTo(Seeker::class, 'seeker_id');
    }
}
