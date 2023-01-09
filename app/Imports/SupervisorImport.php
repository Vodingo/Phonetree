<?php

namespace App\Imports;

use App\Department;
use App\StaffList;
use App\Supervisor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupervisorImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $staff = StaffList::where('active', true)->get(['id', 'first_name', 'last_name', 'other_name']);

        foreach ($collection as $row) {

            $allStaff = $staff;

            $supervisorName = Utils::cleanString($row['supervisor']);

            if (!empty($supervisorName)) {
               
                $filter = $allStaff->filter( function ($value, $key) use ($supervisorName) {

                    $firstLastNames = (stripos($supervisorName, $value['first_name']) !== false) &&
                                             (stripos($supervisorName, $value['last_name']) !== false);

                    $lastOtherNames = (stripos($supervisorName, $value['last_name']) !== false) &&
                                             (stripos($supervisorName, $value['other_name']) !== false);

                    $firstOtherNames = (stripos($supervisorName, $value['first_name']) !== false) &&
                                             (stripos($supervisorName, $value['other_name']) !== false);

                    $allNames = (stripos($supervisorName, $value['first_name']) !== false) &&
                                             (stripos($supervisorName, $value['last_name']) !== false) &&
                                             (stripos($supervisorName, $value['other_name']) !== false);

                    if ($allNames || $firstLastNames || $lastOtherNames || $firstOtherNames) {
                        return $value;
                    }
                });

                if ($filter->count() > 0) {

                    $data = $filter->first();
                    
                    $department = $data->department->first();

                    $supervisor = Supervisor::firstOrCreate([
                        'staff_id' => $data->id,
                        'department_id' => (isset($department) ? $department->id : 0),
                        'created_by' => Auth::user()->id
                    ]);

                    $staffList = StaffList::where('first_name',  Utils::cleanString($row['first_name']))
                                            ->where('last_name',  Utils::cleanString($row['last_name']))
                                            ->where('other_name',  Utils::cleanString($row['other_names']))
                                            ->get();

                    if ($staffList->first()) {
                        $staffList->first()->supervisor()->attach($supervisor, ['created_by' => Auth::user()->id]);
                    }
                }

            }
           
        }
    }
}
