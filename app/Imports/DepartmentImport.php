<?php

namespace App\Imports;

use App\Department;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DepartmentImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $departments = $collection->keyBy(function ($item) {
            return Utils::cleanString($item['department']);
        })->keys();

        foreach ($departments as $department) {
            if (!empty($department)) {
                Department::updateOrCreate(
                    ['name' => $department], 
                    ['created_by' => Auth::user()->id, 'active' => true]
                );
            }
        }
    }
}
