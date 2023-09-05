<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    public function importedData()
    {
        return $this->belongsTo(ImportedData::class);
    }
    
}
