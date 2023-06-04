<?php

namespace App\Imports;

use App\Legacy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LegaciesImport implements ToModel, WithHeadingRow
{
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
    }
}
