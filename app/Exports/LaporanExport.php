<?php

namespace App\Exports;

use App\Models\Reservasi;
use Maatwebsite\Excel\Concerns\FromCollection;

class LaporanExport implements FromCollection
{
    public function collection()
    {
        return Reservasi::all();
    }
}
