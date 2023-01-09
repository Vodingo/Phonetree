<?php

namespace App\Http\Controllers\Admin;

use App\Department;
use App\Http\Controllers\Controller;
use App\StaffList;
use App\Supervisor;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupervisorController extends Controller
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

    public function view()
    {
        return view('supervisors.index');
    }

    public function getDepartmentSelectList()
    {
        try {
            $departments = Department::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']); //KLM

        return response()->json($departments);
        } catch (\Exception $ex) {
            dd('SupervisorController.php -> getDepartmentSelectList : ' .$ex->getMessage());
        }
        
    }

    public function getUnitSelectList()
    {
        try {
            $units = Unit::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']); //KLM

        return response()->json($units);
        } catch (\Exception $ex) {
            dd('SupervisorController.php -> getUnitSelectList : ' .$ex->getMessage());
        }
        
    }

    public function getSupervisorSelectList()
    {
        try {
            $supervisor = new Supervisor();
        $result = $supervisor->list();

        return response()->json($result);
        } catch (\Exception $ex) {
            dd('SupervisorController.php -> getSupervisorSelectList : ' .$ex->getMessage());
        }
        
    }

    public function getSupervisedStaff()
    {
        try {
            $staffList = new StaffList();

        return response()->json($staffList->list());
        } catch (\Exception $ex) {
            dd('SupervisorController.php -> getSupervisedStaff : ' .$ex->getMessage());
        }
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index()
    {
        try {
            $supervisor = new Supervisor();
        $result = $supervisor->list();

        return response()->json($result);
        } catch (\Exception $ex) {
            dd('SupervisorController.php -> index : ' .$ex->getMessage());
        }
        
        
    }

    public function staff(Request $request)
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
            dd('SupervisorController.php -> staff : ' .$ex->getMessage());
        }
        
    }

    public function staffList($supervisorId)
    {
        try {
            $result = [];

        if (isset($supervisorId)) {
            $supervisor = new Supervisor();
            $result = $supervisor->staffList($supervisorId);
        }

        return response()->json($result);
        } catch (\Exception $ex) {
            dd('SupervisorController.php -> staffList : ' .$ex->getMessage());
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'supervisor' => ['required', 'integer'],
            'department' => ['required', 'integer'],
            'unit' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        try {

            $supervisor = Supervisor::where('staff_id', $request->supervisor)->where('active', "true")->get();

            if ($supervisor->count() > 0) {
                return response()->json(['status' => false, 'message' => 'Supervisor already exists in the system']);
            }
    
            $supervisor = Supervisor::create([
                'staff_id' => $request->supervisor,
                'department_id' => $request->department,
                'created_by' => Auth::user()->id,
            ]);
    
            return response()->json(['status' => true, 'message' => 'Supervisor added successfully']);

        } catch (QueryException $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to add supervisor ' . $e->getMessage()]);
          
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supervisor $supervisor)
    {
        $supervisor->active = '0';
        $supervisor->updated_by = Auth::user()->id;
        $supervisor->updated_at = Carbon::now();
        $supervisor->save();

        return response()->json(['status' => true, 'message' => 'Supervisor deleted successfully']);
    }

    public function destroySupervisorStaff($staff)
    {
        DB::table('supervisor_staff')->where('staff_id', $staff)->update(['active' => '0']);

        return response()->json(['status' => true, 'message' => 'Supervisor staff deleted successfully']);
    }

    public function storeSupervisorStaff(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'supervisor' => ['required', 'integer'],
                'staff' => ['required'],
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }   

            foreach ($request->staff as $row) {
    
                $supervisor = DB::table('supervisor_staff')
                    ->join('staff_list', 'staff_list.id', '=', 'supervisor_staff.staff_id')
                    ->where('supervisor_staff.staff_id', $row)
                    ->where('supervisor_staff.active', '1')
                    ->get();                
    
                if ($supervisor->count() > 0) {

                    $supervisorDetails = DB::table('staff_list')
                    ->join('supervisors', 'staff_list.id', '=', 'supervisors.staff_id')
                    ->where('supervisors.id', $supervisor->first()->supervisor_id)
                    ->where('supervisors.active', '1')
                    ->get();     
    
                    $staffName = $supervisor->first()->last_name . ' ' . $supervisor->first()->other_name . ' ' . $supervisor->first()->first_name;

                    $supervisorName = $supervisorDetails->first()->last_name . ' ' . $supervisorDetails->first()->other_name . ' ' . $supervisorDetails->first()->first_name;
    
                    return response()->json(['status' => false, 'message' => $staffName . ' is already assigned to ' .$supervisorName]);
                }
    
                DB::table('supervisor_staff')->where('staff_id', $row)->update(['active' => false]);
    
                DB::table('supervisor_staff')->insert([
                    'supervisor_id' => $request->supervisor,
                    'staff_id' => $row,
                    'created_by' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                ]);
            }

            return response()->json(['status' => true, 'message' => 'Supervisor staff added successfully']);

        } catch (QueryException $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to add supervisor staff ' . $e->getMessage()]);
            // Note any method of class PDOException can be called on $ex.
        }

        

        
    }
}
