<?php

namespace App\Imports;

use App\Legacy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LegaciesImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new Legacy([
            'garden' => $row['garden'],
            'grade' => $row['grade'],
            'invoice' => $row['invoice'],
            'qty' => $row['qty'],
        ]);
    }
}
