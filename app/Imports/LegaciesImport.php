<?php

namespace App\Imports;

use App\Legacy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Log;

class LegaciesImport implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue
{
   public function onRow(Row $row)
{
    $row = $row->toArray();
    
    if (!empty($row[0]) && !empty($row[1]) && !empty($row[2]) && !empty($row[3]) && !empty($row[4])) {
        $legacy = new Legacy();
        $legacy->garden = $row[0];
        $legacy->invoice = $row[1];
        $legacy->qty = $row[2];
        $legacy->grade = $row[3];
        $legacy->package = $row[4];

        $legacy->save();
    } else {
        Log::error('One or more required fields are empty in the row: '.json_encode($row));
    }
}


    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}


