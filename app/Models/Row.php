<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Row extends Model
{
    public function importedData()
    {
        return $this->belongsTo(ImportedData::class);
    }
    
}
