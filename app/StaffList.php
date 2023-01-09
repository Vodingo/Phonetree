<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffList extends Model
{
    protected $table = 'staff_list';

    protected $with = ['department', 'unit', 'supervisor'];

    protected $appends = ['full_name'];

    protected $hidden = ['created_by', 'updated_by', 'created_at', 'updated_at', 'active'];

    protected $fillable = ['first_name', 'last_name', 'other_name', 'work_phone', 'personal_phone', 'secondary_phone', 'username', 'email',
        'employee_number','country', 'created_by','active'];

    public function getFullNameAttribute()
    {
        return ucfirst($this->first_name) . ' ' . ucfirst($this->other_name) . ' ' . ucfirst($this->last_name);
    }

    public function department()
    {
        try {
            return $this->belongsToMany(Department::class, 'department_staff', 'staff_id', 'department_id')->wherePivot('active', '1');
        } catch (\Exception $ex) {
            dd('StaffList.php -> list : ' .$ex->getMessage());
        }

    }

    public function unit()
    {
        try {
            return $this->belongsToMany(Unit::class, 'unit_staff', 'staff_id', 'unit_id')->wherePivot('active', '1');
        } catch (\Exception $ex) {
            dd('StaffList.php -> unit : ' .$ex->getMessage());
        }

    }

    public function supervisor()
    {
        try {
            return $this->belongsToMany(Supervisor::class, 'supervisor_staff', 'staff_id', 'supervisor_id')->wherePivot('active', '1');
        } catch (\Exception $ex) {
            dd('StaffList.php -> supervisor : ' .$ex->getMessage());
        }

    }

    function list() {
        try {

            $staffList = StaffList::where('active', "true")->orderBy('first_name')->get();
            return self::prepareStaffList($staffList);

        } catch (\Exception $ex) {
            dd('StaffList.php -> list : ' .$ex->getMessage());
        }

    }

    public static function filter($filters)
    {
        try {
            $query = StaffList::query();

            if (isset($filters['name'])) {
                
                $input = $filters['name'];
                
                // split user input to words
                $input_array=explode(' ',$input);
                
                $staff='';
                foreach($input_array as $single){
                    $staff.='%'.$single;
                }
                $staff.='%';

                $query->whereRaw('lower(concat(first_name,\' \',other_name,\' \',last_name)) like ? and active = ?', [strtolower($staff),true]);

            }

            if (isset($filters['department'])) {

                $department = $filters['department'];

                $query->whereHas('department', function ($q) use ($department) {
                    $q->where('department_id', $department);
                });
            }

            if (isset($filters['unit'])) {

                $unit = $filters['unit'];

                $query->whereHas('unit', function ($q) use ($unit) {
                    $q->where('unit_id', $unit);
                });
            }

            if (isset($filters['supervisor'])) {

                $supervisor = $filters['supervisor'];

                $query->whereHas('supervisor', function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                });
            }

            $staffList = $query->get();

            return self::prepareStaffList($staffList);
        } catch (\Exception $ex) {
            dd('StaffList.php -> filter : ' .$ex->getMessage());
        }

    }

    public static function prepareStaffList($staffList)
    {
        try {
            $result = [];

            foreach ($staffList as $row) {

                $supervisorObject = $row->supervisor->first();
                $departmentObject = $row->department->first();
                $unitObject = $row->unit->first();

                $departmentName = null;
                $departmentId = null;

                if (isset($departmentObject)) {
                    $departmentName = $departmentObject->name;
                    $departmentId = $departmentObject->id;
                }

                $unitName = null;
                $unitId = null;

                if (isset($unitObject)) {
                    $unitName = $unitObject->name;
                    $unitId = $unitObject->id;
                }

                $supervisorName = null;
                $supervisorId = null;

                if (isset($supervisorObject)) {
                    $staffId = $row->supervisor->first()->staff_id;
                    $supervisorId = $row->supervisor->first()->id;
                    $supervisor = StaffList::find($staffId);
                    $supervisorName = isset($supervisor) ? $supervisor->full_name : null;
                }

                $result[] = array(
                    'id' => $row->id,
                    'full_name' => $row->full_name,
                    'first_name' => $row->first_name,
                    'last_name' => $row->last_name,
                    'other_name' => $row->other_name,
                    'personal_phone' => $row->personal_phone,
                    'work_phone' => $row->work_phone,
                    'secondary_phone' => $row->secondary_phone,
                    'email' => $row->email,
                    'employee_number' => $row->employee_number,
                    'department' => ['id' => $departmentId, 'name' => $departmentName],
                    'unit' => ['id' => $unitId, 'name' => $unitName],
                    'supervisor' => ['id' => $supervisorId, 'name' => $supervisorName],
                    'site' => $row->country,
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('StaffList.php -> prepareStaffList : ' .$ex->getMessage());
        }

    }

}
