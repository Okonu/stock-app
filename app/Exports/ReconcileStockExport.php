<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;


class ReconcileStockExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data['data'];
    }

    public function headings(): array
    {
        return [
            '#',
            'Sys',
            'Phys',
            'Sys_Qty',
            'Phys_Qty',
            'Garden',
            'Grade',
            'Status',
        ];
    }
}
