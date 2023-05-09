<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model{
    protected $fillable = ['warehouse_id', 'bay_id', 'owner_id', 'garden_id', 'grade_id', 'packageType_id', 'qty', 'year', 'invoice'];
    protected $hidden = ['created_at', 'updated_at'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function bay()
    {
        return $this->belongsTo(Bay::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function garden()
    {
        return $this->belongsTo(Garden::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function packageType()
    {
        return $this->belongsTo(PackageType::class);
    }
}