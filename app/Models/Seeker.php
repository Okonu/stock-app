<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Seeker extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'seekers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'password',
        'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the job applications for the seeker.
     */
    // public function jobApplications()
    // {
    //     return $this->hasMany(JobApplication::class);
    // }

    /**
     * Get the interests for the seeker.
     */
    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'seeker_interests');
    }
        /**
     * Get the skills for the seeker.
     */
    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    /**
     * Get the location for the seeker.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the highest level for the seeker.
     */
    public function highestLevel()
    {
        return $this->belongsTo(HighestLevel::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Additional functionality for the seeker user
}
