<?php

namespace App\Imports;

use App\Department;
use App\StaffList;
use App\Unit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TanzaniaStaffImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        StaffList::where('active', true)->where('country', 'Tanzania')->update(['active' => false]);

        foreach ($collection as $row) {

            if (!empty($row['first_name']) && !empty($row['last_name'])) {

                $staff = StaffList::updateOrCreate([
                    'employee_number' => Utils::cleanString($row['employee_number']),
                    'first_name' => Utils::cleanString($row['first_name']),
                    'last_name' => Utils::cleanString($row['last_name']),
                    'other_name' => Utils::cleanString($row['other_names']),
                    'username' => isset($row['username']) ? Utils::cleanString($row['username']) : '',
                    'email' => isset($row['email']) ? Utils::cleanString($row['email']) : '',
                    'work_phone' => Utils::cleanString($row['work_cell']),
                    'personal_phone' => Utils::cleanString($row['personal_cell']),
                    'secondary_phone' => Utils::cleanString($row['secondary_contact_number']),
                    'country' => 'Tanzania'
                ], ['created_by' => Auth::user()->id, 'active' => true]);

                $department = Department::where('name', Utils::cleanString($row['department']))
                    ->where('active', true)
                    ->get();

                $unit = Unit::where('name', Utils::cleanString($row['unit']))
                    ->where('active', true)
                    ->get();

                if ($department->first()) {
                    $staff->department()->attach($department, ['created_by' => Auth::user()->id]);
                }

                if ($unit->first()) {
                    $staff->unit()->attach($unit, ['created_by' => Auth::user()->id]);
                }
            }
        }
    }
}
