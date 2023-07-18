<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_id',
        'company_type_id',
        'business_email',
        'telephone',
        'business_registration_files',
        'business_identification_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    

    public function companyType()
{
    return $this->belongsTo(CompanyType::class, 'company_type_id');
}

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
