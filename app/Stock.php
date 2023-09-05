<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stocks';

    protected $fillable = ['user_id', 'warehouse_id', 'warehouse_bay_id', 'owner_id', 'grade_id', 'package_id', 'invoice', 'qty', 'year', 'remark', 'mismatch', 'comment'];

    protected $hidden = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function bays()
    {
        return $this->belongsTo(WarehouseBay::class, 'warehouse_bay_id');
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

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
