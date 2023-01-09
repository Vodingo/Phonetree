<?php

namespace App\Http\Controllers\Admin;

use App\Department;
use App\Http\Controllers\Common\Status;
use App\Http\Controllers\Controller;
use App\Register;
use App\RollCallSession;
use App\StaffList;
use App\Supervisor;
use App\Unit;
use Illuminate\Http\Request;

class ManagementDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Show the management dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('management.dashboard');
    }

    public function getSessionSelectList()
    {
        try {
            $rollCallSession = new RollCallSession();
            $sessions = $rollCallSession->getDashboardRollCallSessionSelectList();

            return response()->json($sessions);
        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> getSessionSelectList : ' . $ex->getMessage());
        }

    }

    public function getDepartmentSelectList()
    {
        try {

            $departments = Department::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']);

            return response()->json($departments);

        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> getDepartmentSelectList : ' . $ex->getMessage());
        }

    }

    public function getUnitSelectList()
    {
        try {

            $units = Unit::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']);

            return response()->json($units);

        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> getUnitSelectList : ' . $ex->getMessage());
        }

    }

    public function getSupervisorSelectList()
    {
        try {
            $supervisor = new Supervisor();
            $result = $supervisor->list();

            return response()->json($result);
        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> getSupervisorSelectList : ' . $ex->getMessage());
        }

    }

    public function summary($sessionId)
    {
        try {

            $accountedCount = Register::where('session_id', $sessionId)->where('status_id', Status::ACCOUNTED)->where('active', '1')->count();
            $unaccountedCount = Register::where('session_id', $sessionId)->where('status_id', Status::UNACCOUNTED)->where('active', '1')->count();
            $totalStaff = StaffList::where('active', '1')->count();
            $staffNotContacted = $totalStaff - ($accountedCount + $unaccountedCount);

            return response()->json([
                'accountedCount' => $accountedCount, //(int)(($accountedCount/$totalStaff)*100),
                'unaccountedCount' => $unaccountedCount, //(int)(($unaccountedCount/$totalStaff)*100)
                'staffNotContacted' => $staffNotContacted,
                'totalStaff' => $totalStaff,
            ]);

            /*return response()->json($units);*/

        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> summary : ' . $ex->getMessage());
        }

    }

    public function getAccountedStaff(Request $request)
    {
        try {

            $session = $request->session;
            $department = $request->department;
            $unit = $request->unit;
            $supervisor = $request->supervisor;
            $staff = $request->staff;

            $register = new Register();
            $accountedStaff = $register->contactedStaff($session, $department, $unit, $supervisor, $staff, Status::ACCOUNTED);

            return response()->json($accountedStaff);

        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> getAccountedStaff : ' . $ex->getMessage());
        }

    }

    public function getUnaccountedStaff(Request $request)
    {
        try {
            $session = $request->session;
            $department = $request->department;
            $unit = $request->unit;
            $supervisor = $request->supervisor;
            $staff = $request->staff;

            $register = new Register();
            $unaccountedStaff = $register->contactedStaff($session, $department, $unit, $supervisor, $staff, Status::UNACCOUNTED);

            return response()->json($unaccountedStaff);

        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> getUnaccountedStaff : ' . $ex->getMessage());
        }

    }

    public function getStaffNotContacted(Request $request)
    {
        try {
            $session = $request->session;
            $department = $request->department;
            $unit = $request->unit;
            $supervisor = $request->supervisor;
            $staff = $request->staff;

            $register = new Register();
            $notcontacted = $register->staffNotContacted($session, $department, $unit, $supervisor, $staff);

            return response()->json($notcontacted);

        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> getStaffNotContacted : ' . $ex->getMessage());
        }

    }

    public function getSupervisorsSummary(Request $request)
    {
        try {
            $register = new Register();
            $supervisorSummary = $register->getSupervisorsData($request->session, $request->filter);

            return response()->json($supervisorSummary);

        } catch (\Exception $ex) {
            dd('ManagementDashboardController.php -> getSupervisorsSummary : ' . $ex->getMessage());
        }

    }
}
