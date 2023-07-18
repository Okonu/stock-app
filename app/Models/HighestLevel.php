<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HighestLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function seekers()
    {
        return $this->belongsToMany(Seeker::class);
    }

    // public function seekerProfiles()
    // {
    //     return $this->belongsToMany(SeekerProfile::class);
    // }
}
