<?php

namespace App\Imports;

use App\Models\ImportedData;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Row;

class StockImport implements OnEachRow, WithStartRow, WithChunkReading, ShouldQueue
{
    private $importedData;
    private $rowData = [];

    public function __construct()
    {
        $this->importedData = new ImportedData();
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $this->rowData[] = [
            'garden' => $row[0],
            'invoice' => $row[1],
            'qty' => $row[2],
            'grade' => $row[3],
            'package' => $row[4],
        ];
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

    public function __destruct()
    {
        $this->importedData->rows = $this->rowData;
        $this->importedData->save();
    }
}


