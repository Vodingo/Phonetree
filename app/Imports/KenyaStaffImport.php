<?php

namespace App\Imports;

use App\Department;
use App\StaffList;
use App\Unit;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KenyaStaffImport implements SkipsEmptyRows,ToModel, WithHeadingRow
{
    public function model(array $row)
    {        
        
        $exists = Stafflist::where('id',$row['id'])->first();

        if ($exists && $row['email_address'] !=null) {
          
            $exists->update([
                'email'=>trim($row['email_address']),
                'employee_number' => trim($row['employee_number']),
                'personal_phone' =>trim($row['personal_phone']),
                'work_phone' =>trim($row['work_phone']),
                'updated_by' =>Auth::user()->id,
                'active'=>$row['employement_status'] == "On_Staff" ? true : false
            ]);

            return $exists;
        }
        else
        {
            if($row["first_name"] ==null && $row['last_name'] ==null)
            {
                return null;
            }
            else
            {
                $existss = Stafflist::where(['email'=>$row['email_address']])->first();

                if ($existss) {
                
                    $existss->update([
                        'email'=>trim($row['email_address']),
                        'employee_number' => trim($row['employee_number']),
                        'personal_phone' =>trim($row['personal_phone']),
                        'work_phone' =>trim($row['work_phone']),
                        'updated_by' =>Auth::user()->id,
                        'active'=>$row['employement_status'] == "On_Staff" ? true : false
                    ]);
        
                    return $existss;
                }
                else
                {
                    return new Stafflist([          
                        'first_name'     => trim($row['first_name']),
                        'last_name'    => trim($row['last_name']), 
                        'other_name'     => trim($row['other_names']),
                        'work_phone'    => trim($row['work_phone']), 
                        'personal_phone'     => trim($row['personal_phone']),
                        'email'    => trim($row['email_address']), 
                        'country'    => trim($row['location']), 
                        'employee_number' => trim($row['employee_number']),
                        'created_by'    =>  Auth::user()->id,
                        'active'=>$row['employement_status'] == "On_Staff" ? true : false
                    ]);
                }
        }             
    }
           
}
}


    /**
     * @param Collection $collection
     */
   /* public function collection(Collection $collection)
    {
        StaffList::where('active', true)->where('country', 'Kenya')->update(['active' => false]);

        foreach ($collection as $row) {

            if (!empty($row['first_name']) && !empty($row['last_name'])) {

                $staff = StaffList::firstOrCreate([
                    'employee_number' => Utils::cleanString($row['employee_number']),
                    'first_name' => Utils::cleanString($row['first_name']),
                    'last_name' => Utils::cleanString($row['last_name']),
                    'other_name' => Utils::cleanString($row['other_names']),
                    'username' => isset($row['username']) ? Utils::cleanString($row['username']) : '',
                    'email' => isset($row['email']) ? Utils::cleanString($row['email']) : '',
                    'work_phone' => Utils::cleanString($row['work_cell']),
                    'personal_phone' => Utils::cleanString($row['personal_cell']),
                    'secondary_phone' => Utils::cleanString($row['secondary_contact_number']),
                    'country' => 'Kenya',
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
    }*/

