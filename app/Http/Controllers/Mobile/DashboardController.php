<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Common\Status;
use App\Http\Controllers\Controller;
use App\Register;
use App\RollCallSession;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function index()
    {
        $rollCallSession = new RollCallSession();
        $sessions = $rollCallSession->active();

        return view('mobile.dashboard', ['sessions' => $sessions]);
    }

    public function accounted(Request $request)
    {
        $sessionId = $request->session;

        $register = new Register();
        $staff = $register->contactedStaff($sessionId, $department = null, $unit = null, $supervisor = null, $staff = null, Status::ACCOUNTED);

        $session = RollCallSession::find($sessionId);
        $title = "Accounted Staff";

        return view('mobile.staff-list', ['staff' => $staff, 'session' => $session, 'title' => $title, 'contacted' => 1]);
    }

    public function unAccounted(Request $request)
    {
        $sessionId = $request->session;

        $register = new Register();
        $staff = $register->contactedStaff($sessionId, $department = null, $unit = null, $supervisor = null, $staff = null, Status::UNACCOUNTED);

        $session = RollCallSession::find($sessionId);
        $title = "Unaccounted Staff";

        return view('mobile.staff-list', ['staff' => $staff, 'session' => $session, 'title' => $title, 'contacted' => 1]);
    }

    public function notContacted(Request $request)
    {
        $sessionId = $request->session;

        $register = new Register();
        $staff = $register->staffNotContacted($sessionId,  $department = null, $unit = null, $supervisor = null, $staff = null);

        $session = RollCallSession::find($sessionId);
        $title = "Staff not contacted";

        return view('mobile.staff-list', ['staff' => $staff, 'session' => $session, 'title' => $title, 'contacted' => 0]);
    }
}
