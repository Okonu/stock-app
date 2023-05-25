<?php

namespace App\Imports;

use App\Legacy;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LegaciesImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     * @return Legacy|null
     */
    public function model(array $row)
    {
        // Check if all the required columns have values
        if (empty($row['garden']) || empty($row['grade']) || empty($row['invoice']) || empty($row['qty'])) {
            return null;
        }

        return new Legacy([
            'garden' => $row['garden'],
            'invoice' => $row['invoice'],
            'qty' => $row['balance_qty'],
            'grade' => $row['grade'],
            'package' => $row['package_type'],
        ]);
    }
}
