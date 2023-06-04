<?php

namespace App\Exports;

use App\Stock;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class ExportStock implements FromView
{
    use Exportable;

    public function view(): View
    {
        return view('stocks.stockAllExcel', [
            'stock' => Stock::all(),
        ]);
    }
}
