<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = ['interest'];


    public function seekers()
        {
            return $this->belongsToMany(Seeker::class, 'seeker_interests');
        }

    public function seekerProfiles()
    {
        return $this->belongsToMany(SeekerProfile::class, 'seeker_interests', 'interest_id', 'seeker_id');
    }
}
