<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StaffListImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Nairobi' => new KenyaStaffImport(),
           // 'Pretoria' => new PretoriaStaffImport(),
           // 'Tanzania' => new TanzaniaStaffImport(),
        ];
    }
}
