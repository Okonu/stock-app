<?php

namespace App\Imports;

use App\Legacy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class LegaciesImport implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue
{
<<<<<<< HEAD
    public function onRow(Row $row)
    {
        $row = $row->toArray();

        // Validate that the required fields are not empty
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
=======
    /**
     * @return Legacy|null
     */
    public function model(array $row)
    {
        // dd($row);
        // Check if all the required columns have values
        if (empty($row[0]) || empty($row[1]) || empty($row[2]) || empty($row[3]) || empty($row[4])) {
            return null;
        }

        return new Legacy([
            'garden' => $row[0],
            'invoice' => $row[1],
            'qty' => $row[2],
            'grade' => $row[3],
            'package' => $row[4],
        ]);
>>>>>>> db5dfd542f7844059e5c01268826fe8f09812183
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
