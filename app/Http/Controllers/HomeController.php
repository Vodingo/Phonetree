<?php

namespace App\Http\Controllers;

use App\User;
use App\Status;
use App\Register;
use App\Supervisor;
use App\StaffList;
use App\RollCallSession;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Jenssegers\Agent\Agent;
use App\Http\Controllers\Controller;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $agent = new Agent();

        
        if ($agent->isDesktop()) {             
            
            return view('home');            
        }
    
        $supervisor = new Supervisor();        
        $supervisors = $supervisor->list();

        $rollCallSession = new RollCallSession();
        
        $sessions = $rollCallSession->getHomeRollCallSessionSelectList();

        return view('mobile.index', ['sessions' => $sessions, 'supervisors' => $supervisors]);
        } catch (\Exception $ex) {

            dd('HomeController.php -> index : ' . $ex->getMessage());

        }
        
    }

    public function getSessionSelectList()
    {
        try {
            $rollCallSession = new RollCallSession();
        
        $sessions = $rollCallSession->getHomeRollCallSessionSelectList();

        return response()->json($sessions);
        } catch (\Exception $ex) {
            dd('HomeController.php -> getSessionSelectList : ' . $ex->getMessage());
        }
        
    }

    public function getSupervisorSelectList()
    {
        try {
            $supervisor = new Supervisor();
        $supervisors = $supervisor->getHomeSupervisorSelectList();
        
        return response()->json($supervisors);
        } catch (\Exception $ex) {
            dd('HomeController.php -> getSupervisorSelectList : ' . $ex->getMessage());
        }
        
    }

    public function getStatusSelectList()
    {
        try {
            $status = new Status();
        $results = $status->getHomeRegisterStatusSelectList();

        return response()->json($results);
        } catch (\Exception $ex) {
            dd('HomeController.php -> getStatusSelectList : ' . $ex->getMessage());
        }
        
    }

    public function getSupervisors()
    {
        try {
            $supervisor = new Supervisor();
        $supervisors = $supervisor->list();

        return $supervisors;
        } catch (\Exception $ex) {
            dd('HomeController.php -> getSupervisors : ' . $ex->getMessage());
        }
        
    }

    public function getSupervisedStaff(Request $request)
    {
        try {
            $result = [];

        $session = $request->session;
        $supervisorId = $request->supervisor;

        if (isset($supervisorId)) {
            $supervisor = new Supervisor();
            $result = $supervisor->staff($session, $supervisorId);
        }
         
        return response()->json($result);
        } catch (\Exception $ex) {
            dd('HomeController.php -> getSupervisedStaff : ' . $ex->getMessage());
        }
        
    }

    public function registerStaffAttendance(Request $request)
    {
        $session = $request->session;
        $staff = $request->staff;
        $staffName = $request->staffName;
        $status_id = $request->status_id;
        $comments = $request->comments;

        if (!isset($session)) {
            return response()->json(['status' => false, 'message' => 'Please select the roll call session']);
        }

        if (!isset($staff)) {
            return response()->json(['status' => false, 'message' => 'Please select the staff to update']);
        }

        if (!isset($status_id)) {
            return response()->json(['status' => false, 'message' => 'Please select the status of ' . $staffName]);
        }

        DB::beginTransaction();

        try {

            Register::where('staff_id', $staff)
                ->where('session_id', $session)
                ->where('active', true)
                ->update(['active' => false, 'updated_by' => Auth::user()->id]);


            $register = new Register();
            $register->session_id = $session;
            $register->staff_id = $staff;
            $register->status_id = $status_id;
            $register->comments = $comments;
            $register->active = true;
            $register->created_by = Auth::user()->id;
            $register->save();

            DB::commit();

            return  response()->json(['status' => true, 'message' => 'Register updated successfully']);
        } catch (\Exception $ex) {

            DB::rollBack();

            return  response()->json(['status' => false, 'message' => 'An error occured while updating staff register', 'error' => $ex->getMessage()]);
        }
    }
}
