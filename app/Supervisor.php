<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $hidden = ['created_by', 'updated_by', 'created_at', 'updated_at', 'active'];
    protected $fillable = ['staff_id', 'department_id', 'created_by'];

    public function getHomeSupervisorSelectList()
    {
        try {

            $supervisors = $this::where('active', '1')->get();

            $data = [];

            foreach ($supervisors as $supervisor) {

                $supervisorData = StaffList::find($supervisor->staff_id);

                if (isset($supervisorData)) {

                    $departmentObject = $supervisorData->department->first();
                    $unitObject = $supervisorData->unit->first();

                    $department = null;

                    if (isset($departmentObject)) {
                        $department = $departmentObject->name;
                    }

                    $unit = null;

                    if (isset($unitObject)) {
                        $unit = $unitObject->name;
                    }

                    $data[] = array(
                        'id' => $supervisor->id,
                        'name' => $supervisorData->full_name,
                        'department' => $department,
                        'unit' => $unit,
                    );
                }
            }

            $sorted = collect($data)->sortBy('name');
            $result = $sorted->values()->all();

            return $result;

        } catch (\Exception $ex) {

            dd('Supervisor.php -> getHomeSupervisorSelectList : ' .$ex->getMessage());

        }

    }

    function list() {

        try {

            //KLM
            $supervisors = $this::where('active', '1')->get();
            $data = [];

            foreach ($supervisors as $supervisor) {

                $supervisorData = StaffList::find($supervisor->staff_id);

                if (isset($supervisorData)) {

                    $departmentObject = $supervisorData->department->first();
                    $unitObject = $supervisorData->unit->first();

                    $department = null;

                    if (isset($departmentObject)) {
                        $department = $departmentObject->name;
                    }

                    $unit = null;

                    if (isset($unitObject)) {
                        $unit = $unitObject->name;
                    }

                    $data[] = array(
                        'id' => $supervisor->id,
                        'name' => $supervisorData->full_name,
                        'department' => $department,
                        'unit' => $unit,
                    );
                }
            }

            $sorted = collect($data)->sortBy('name');
            $result = $sorted->values()->all();
            return $result;

        } catch (\Exception $ex) {

            dd('Supervisor.php -> list : ' .$ex->getMessage());

        }

    }

    public function staff($session, $supervisor)
    {
        try {

            $staff = StaffList::whereHas('supervisor', function ($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor);
            })
                ->leftJoin('register', function ($join) use ($session) {
                    $join->on('staff_list.id', '=', 'register.staff_id')
                        ->where('register.session_id', '=', $session)
                        ->where('register.active', '=', '1');
                })
                ->leftJoin('statuses', 'statuses.id', '=', 'register.status_id')
                ->select('staff_list.id as id', 'first_name', 'other_name', 'last_name',
                    'work_phone', 'personal_phone', 'secondary_phone', 'status_id', 'statuses.name as status_description',
                    'comments', 'register.id AS contacted')
                ->where('staff_list.active', '1')
                ->orderBy('first_name', 'ASC')
                ->get();

            $result = [];

            foreach ($staff as $row) {
                $result[] = array(
                    'id' => $row->id,
                    'name' => $row->first_name . ' ' . $row->other_name . ' ' . $row->last_name,
                    'work_phone' => $row->work_phone,
                    'personal_phone' => $row->personal_phone,
                    'secondary_phone' => $row->secondary_phone,
                    'status_id' => $row->status_id,
                    'status_description' => $row->status_description,
                    'comments' => $row->comments,
                    'contacted' => (is_null($row->contacted) ? 0 : 1),
                );
            }

            return $result;

        } catch (\Exception $ex) {

            dd('Supervisor.php -> staff : ' .$ex->getMessage());

        }

    }

    public function staffList($supervisor)
    {

        try {

            $staff = StaffList::whereHas('supervisor', function ($query) use ($supervisor) {
                $query->where('supervisor_id', $supervisor)->where('staff_list.active', '1');
            })->get();

            $result = [];

            foreach ($staff as $row) {

                $departmentObject = $row->department->first();
                $unitObject = $row->unit->first();

                $department = null;

                if (isset($departmentObject)) {
                    $department = $departmentObject->name;
                }

                $unit = null;

                if (isset($unitObject)) {
                    $unit = $unitObject->name;
                }

                $result[] = array(
                    'id' => $row->id,
                    'name' => $row->full_name,
                    'work_phone' => $row->work_phone,
                    'personal_phone' => $row->personal_phone,
                    'secondary_phone' => $row->secondary_phone,
                    'department' => $department,
                    'unit' => $unit,
                );
            }

            return $result;

        } catch (\Exception $ex) {

            dd('Supervisor.php -> staffList : ' .$ex->getMessage());

        }

    }
}
