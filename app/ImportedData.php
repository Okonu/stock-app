<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImportedData extends Model
{
    protected $table = 'imported_data';

    protected $fillable = [
        'file_name',
        'rows',
    ];

    protected $casts = [
        'rows' => 'array',
    ];

    public function getRowsAttribute($value)
    {
        return json_decode($value);
    }

    public function rows()
    {
        return $this->hasMany(Row::class);
    }

}
