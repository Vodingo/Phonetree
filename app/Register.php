<?php

namespace App;

use App\Http\Controllers\Common\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Register extends Model
{
    protected $table = 'register';

    protected $with = ['staff', 'session', 'user'];

    protected $hidden = ['created_by', 'updated_by', 'created_at', 'updated_at', 'active'];

    public function staff()
    {
        return $this->belongsTo(StaffList::class, 'staff_id');
    }

    public function session()
    {
        return $this->belongsTo(RollCallSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public static function entries($session, $user)
    {
        try {
            $entries = Register::where('session_id', $session)->where('user_id', $user)->where('active', "true")->get();

            $result = [];

            foreach ($entries as $entry) {
                $result[] = array(
                    'id' => $entry->id,
                    'staff' => $entry->staff->name,
                    'accounted' => ($entry->accounted ? 'Yes' : 'No'),
                    'comment' => $entry->comment,
                    'addedby' => $entry->user->name,
                    'date_added' => $entry->created_at,
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('Register.php -> entries : '+ $ex->getMessage());
        }

    }

    public function contactedStaff($session, $department, $unit, $supervisor, $staff, $accounted)
    {
        try {
            $query = Register::query();

            $query->where('session_id', $session)->where('status_id', $accounted)->where('active', "true");

            if (isset($department)) {
                $query->whereHas('staff', function ($q) use ($department) {
                    $q->whereHas('department', function ($q1) use ($department) {
                        $q1->where('department_id', $department);
                    });
                });
            }

            if (isset($unit)) {
                $query->whereHas('staff', function ($q) use ($unit) {
                    $q->whereHas('unit', function ($q1) use ($unit) {
                        $q1->where('unit_id', $unit);
                    });
                });
            }

            if (isset($supervisor)) {
                $query->whereHas('staff', function ($q) use ($supervisor) {
                    $q->whereHas('supervisor', function ($q1) use ($supervisor) {
                        $q1->where('supervisor_id', $supervisor);
                    });
                });
            }

            if (isset($staff)) {

                $staff = '%' . strtolower($staff) . '%';

                $query->whereHas('staff', function ($q) use ($staff) {
                    $q->whereRaw('LOWER(last_name) like ? OR LOWER(other_name) like ? OR LOWER(first_name) like ?',
                        [$staff, $staff, $staff]);
                });
            }

            $result = $query->get();

            return $this->formatResults($result);
        } catch (\Exception $ex) {
            dd('Register.php -> contactedStaff : '+ $ex->getMessage());
        }

    }

    public function staffNotContacted($session, $department, $unit, $supervisor, $staff)
    {
        try {
            $query = StaffList::query();

            if (isset($session)) {
                $query->whereNotIn('id', function ($query) use ($session) {
                    $query->select('staff_id')->from('register')->where('session_id', $session);
                });
            }

            if (isset($department) && $department != 0) {
                $query->whereHas('department', function ($q) use ($department) {
                    $q->where('department_id', $department);
                });
            }

            if (isset($unit) && $unit != 0) {
                $query->whereHas('unit', function ($q) use ($unit) {
                    $q->where('unit_id', $unit);
                });
            }

            if (isset($supervisor) && $supervisor != 0) {
                $query->whereHas('supervisor', function ($q) use ($supervisor) {
                    $q->where('supervisor_id', $supervisor);
                });
            }

            if (isset($staff)) {

                $staff = '%' . strtolower($staff) . '%';

                $query->whereRaw('LOWER(last_name) like ? OR LOWER(other_name) like ? OR LOWER(first_name) like ?', [$staff, $staff, $staff]);
            }

            $staffNotContacted = $query->get();

            $result = [];

            foreach ($staffNotContacted as $row) {

                $supervisorObject = $row->supervisor->first();
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

                $supervisorName = null;

                if (isset($supervisorObject)) {
                    $supervisorId = $row->supervisor->first()->staff_id;
                    $supervisor = StaffList::find($supervisorId);
                    $supervisorName = isset($supervisor) ? $supervisor->full_name : null;
                }

                $result[] = array(
                    'id' => $row->id,
                    'staff_name' => $row->first_name . ' ' . $row->other_name . ' ' . $row->last_name,
                    'personal_phone' => $row->personal_phone,
                    'work_phone' => $row->work_phone,
                    'secondary_phone' => $row->secondary_phone,
                    'department' => $department,
                    'unit' => $unit,
                    'supervisor' => $supervisorName,
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('Register.php -> staffNotContacted : '+ $ex->getMessage());
        }

    }

    public function formatResults($records)
    {
        try {
            $result = [];

            foreach ($records as $row) {

                $supervisor = $row->staff->supervisor->first();
                $supervisorId = $supervisor->staff_id;
                $supervisor = StaffList::find($supervisorId);

                $department = $row->staff->department->first();
                $unit = $row->staff->unit->first();

                $result[] = array(
                    'id' => $row->id,
                    'staff_name' => $row->staff->full_name,
                    'personal_phone' => $row->staff->personal_phone,
                    'work_phone' => $row->staff->work_phone,
                    'secondary_phone' => $row->staff->secondary_phone,
                    'accounted' => (($row->status_id == 1) ? 'Yes' : 'No'),
                    'comments' => $row->comments,
                    'date_updated' => (Carbon::parse($row->created_at))->format('d-M-Y H:i:s A'),
                    'updated_by' => (!empty($row->user) ? $row->user->name : ''),
                    'department' => (!empty($department) ? $department->name : ''),
                    'unit' => (!empty($unit) ? $unit->name : ''),
                    'supervisor' => isset($supervisor) ? $supervisor->full_name : null,
                );
            }

            return $result;
        } catch (\Exception $ex) {
            dd('Register.php -> formatResults : '+ $ex->getMessage());
        }

    }

    public function supervisorsSummary($session)
    {
        try {
            $accountedQuery = $this->getSupervisorCount($session, Status::ACCOUNTED);
            $unaccountedQuery = $this->getSupervisorCount($session, Status::UNACCOUNTED);

            $supervisors = DB::table('supervisors AS s')
                ->select(
                    's.id',
                    'sl.work_phone', 'sl.personal_phone', 'sl.secondary_phone',
                    DB::raw("CONCAT(first_name, ' ',other_name, ' ',last_name) AS supervisor"),
                    DB::raw('count(*) as total'),
                    DB::raw('( ' . $accountedQuery . ' ) as accounted'),
                    DB::raw('( ' . $unaccountedQuery . ' ) as unaccounted')
                )
                ->join('staff_list AS sl', 'sl.id', '=', 's.staff_id')
                ->leftJoin('supervisor_staff AS ss', 'ss.supervisor_id', '=', 's.id')
                ->where('s.active', "true")
                ->where('ss.active', "true")
                ->orderBy('first_name', 'ASC')
                ->groupBy('s.id', 'first_name', 'other_name', 'last_name', 'work_phone', 'personal_phone', 'secondary_phone')
                ->get();

            return $supervisors;
        } catch (\Exception $ex) {
            dd('Register.php -> supervisorsSummary '+ $ex->getMessage());
        }

    }

    protected function getSupervisorCount($session, $status)
    {
        return 'SELECT COUNT(*) FROM register r
        INNER JOIN supervisor_staff sup ON r.staff_id = sup.staff_id
        WHERE r.active = true
        AND sup.active = true
        AND sup.supervisor_id = s.id
        AND r.session_id = ' . $session . '
        AND r.status_id = ' . $status;
    }

    public function getSupervisorsData($session, $filter)
    {
        try {
            $supervisors = $this->supervisorsSummary($session);

            $results = $supervisors->map(function ($supervisor) {
                $supervisor->not_contacted = $supervisor->total - ($supervisor->accounted + $supervisor->unaccounted);
                return $supervisor;
            });

            if ($filter == 'true') {
                $filteredResults = $results->where('not_contacted', '>', 0)->values();

                return $filteredResults;
            }

            return $results;
        } catch (\Exception $ex) {
            dd('Register.php -> getSupervisorsData : '+ $ex->getMessage());
        }

    }
}
