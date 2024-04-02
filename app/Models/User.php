<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'password', 'role', 'token', 'token_expires_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isClerk()
    {
        return $this->role === 'clerk';
    }

    public function isStaff()
    {
        return $this->role === 'staff';
    }

    public function isTokenEnabled()
    {
        if ($this->isClerk() && $this->token_expires_at !== null && $this->token_expires_at >= Carbon::now()) {
            return true;
        }
        return false;
    }

    public function generateToken()
    {
        $token = Str::random(32);
        $this->token = $token;
        $this->token_expires_at = Carbon::now()->addDay();
        $this->save();

        return $token;
    }
}
