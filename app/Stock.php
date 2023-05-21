<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    //
    protected $table = 'stocks';

    protected $fillable = ['warehouse_id','bay_id', 'owner_id', 'grade_id','package_id', 'invoice', 'qty', 'year', 'remark', 'mismatch'];

    protected $hidden = ['created_at','updated_at'];

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

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
