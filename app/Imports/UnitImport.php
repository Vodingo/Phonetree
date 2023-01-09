<?php

namespace App\Imports;

use App\Unit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnitImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $units = $collection->keyBy(function ($item) {
            return Utils::cleanString($item['unit']);
        })->keys();
        
        foreach ($units as $unit) {
            if (!empty($unit)) {
                Unit::updateOrCreate(
                    ['name' => $unit], 
                    ['created_by' => Auth::user()->id, 'active' => true]
                );
            }
        }
    }
}
