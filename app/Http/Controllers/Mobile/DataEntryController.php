<?php

namespace App\Http\Controllers\Mobile;

use DateTime;

use App\StaffList;
use App\Supervisor;
use App\RollCallSession;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DataEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function index()
    {
        $rollCallSession = new RollCallSession();
        $sessions = $rollCallSession->active();

        $supervisor = new Supervisor();
        $supervisors = $supervisor->list();

        $defaultSession = 'Roll Call '.(new DateTime())->format('d M Y');

        return view('mobile.index', ['sessions' => $sessions, 'supervisors' => $supervisors, 'defaultSession' => $defaultSession]);
    }

    public function showEntryForm($session, $staffId)
    {

        $staff = StaffList::with('supervisor')
                                ->leftJoin('register',  function ($join) use ($session) {
                                    $join->on('staff_list.id', '=', 'register.staff_id')
                                            ->where('register.session_id', '=', $session)
                                            ->where('register.active', '=', true);
                                })
                                ->select('staff_list.id as id', 'first_name','other_name','last_name', 'work_phone', 'personal_phone', 
                                    'secondary_phone', 'status_id AS accounted', 'comments')
                                ->where('staff_list.id', $staffId)
                                ->orderBy('first_name', 'ASC')
                                ->get();

        $staff = $staff->first();
        
        $supervisorId = $staff->supervisor->first()->staff_id;
        $supervisor = StaffList::where('id', $supervisorId)->first();

        $staff['supervisor_name'] =  $supervisor->first_name . ' ' . $supervisor->other_name . ' ' . $supervisor->last_name;
        
        return view('mobile.entry-form', ['staff' => $staff]);
    }

}
