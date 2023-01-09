<?php

namespace App\Http\Controllers\Admin;

use App\Department;
use App\Http\Controllers\Controller;
use App\Imports\DepartmentImport;
use App\Imports\StaffListImport;
use App\Imports\SupervisorImport;
use App\Imports\UnitImport;
use App\StaffList;
use App\Supervisor;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class StaffListController extends Controller
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

    public function getDepartmentSelectList()
    {
        try {
            $departments = Department::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']);

            return response()->json($departments);
        } catch (\Exception $ex) {
            dd('StaffListController.php -> getDepartmentSelectList : ' . $ex->getMessage());
        }

    }

    public function getUnitSelectList()
    {
        try {
            $units = Unit::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']);

            return response()->json($units);
        } catch (\Exception $ex) {
            dd('StaffListController.php -> getUnitSelectList : ' . $ex->getMessage());
        }

    }

    public function getSupervisorSelectList()
    {
        try {
            $supervisor = new Supervisor();
            $result = $supervisor->list();

            return response()->json($result);
        } catch (\Exception $ex) {
            dd('StaffListController.php -> getSupervisorSelectList : ' . $ex->getMessage());
        }

    }

    public function view()
    {
        $permissions = auth()->user()->roles()->first()->permissions;        

        return view('staff.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            
            $staffList = new StaffList();
          
            return response()->json($staffList->list());
        } catch (\Exception $ex) {
            dd('StaffListController.php -> index : ' . $ex->getMessage());
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
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'othername' => '',
            'work_phone' => '',
            'personal_phone' => '',
            'secondary_phone' => '',
            'email' => ['required', 'string', 'max:255'],
            'employee_no' => ['required', 'string', 'max:255'],
            'department' => ['required', 'integer'],
            'unit' => ['required', 'integer'],
            'supervisor' => ['required', 'integer'],
            'site' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

       
        DB::beginTransaction();

        try {

            $staff = StaffList::create([
                'first_name' => $request->firstname,
                'last_name' => $request->lastname,
                'other_name' => $request->othername,
                'department' => $request->department,
                'work_phone' => $request->work_phone,
                'personal_phone' => $request->personal_phone,
                'secondary_phone' => $request->secondary_phone,
                'email' => $request->email,
                'employee_number' => $request->employee_no,
                'unit' => $request->unit,
                'supervisor' => $request->supervisor,
                'country' => $request->site,
                'created_by' => Auth::user()->id,
            ]);

            $staff->department()->attach($request->department, ['created_by' => Auth::user()->id]);
            $staff->unit()->attach($request->unit, ['created_by' => Auth::user()->id]);
            $staff->supervisor()->attach($request->supervisor, ['created_by' => Auth::user()->id]);
            
            DB::commit();

            return response()->json(['status' => true, 'message' => 'Staff added successfully']);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => false, 'message' => 'An error occured while saving the staff details. Please contact IT']);
        }

    }

    public function edit(Request $request, StaffList $staffList)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'othername' => '',
            'work_phone' => '',
            'personal_phone' => '',
            'secondary_phone' => '',
            'department' => ['required', 'integer'],
            'unit' => ['required', 'integer'],
            'supervisor' => ['required', 'integer'],
            'site' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        DB::beginTransaction();

        try {

            $staffList->first_name = $request->firstname;
            $staffList->last_name = $request->lastname;
            $staffList->other_name = $request->othername;
            $staffList->work_phone = $request->work_phone;
            $staffList->personal_phone = $request->personal_phone;
            $staffList->secondary_phone = $request->secondary_phone;
            $staffList->email = $request->email;
            $staffList->employee_number = $request->employee_number;
            $staffList->country = $request->site;
            $staffList->updated_by = Auth::user()->id;
            $staffList->updated_at = Carbon::now();
            $staffList->save();

            $staffList->department()->newPivotStatement()->where('staff_id', $staffList->id)->update(['active' => '0']);
            $staffList->unit()->newPivotStatement()->where('staff_id', $staffList->id)->update(['active' => '0']);
            $staffList->supervisor()->newPivotStatement()->where('staff_id', $staffList->id)->update(['active' => '0']);

            $staffList->department()->attach($request->department, ['created_by' => Auth::user()->id]);
            $staffList->unit()->attach($request->unit, ['created_by' => Auth::user()->id]);
            $staffList->supervisor()->attach($request->supervisor, ['created_by' => Auth::user()->id]);

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Staff data updated successfully']);

        } catch (\Exception $ex) {

            DB::rollBack();

            return response()->json(['status' => false, 'message' => 'An error occured while updating the staff details. Please contact IT' . $ex->getMessage()]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaffList  $staffList
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffList $staffList)
    {
        DB::beginTransaction();

        try {
            $staffList->active = '0';
            $staffList->save();

            // clean up department, unit and supervisor
            $staffList->department()->newPivotStatement()->where('staff_id', $staffList->id)->update(['active' => '0']);
            $staffList->unit()->newPivotStatement()->where('staff_id', $staffList->id)->update(['active' => '0']);
            $staffList->supervisor()->newPivotStatement()->where('staff_id', $staffList->id)->update(['active' => '0']);

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Staff disabled from the system successfully']);
        } catch (\Exception $ex) {
            
            DB::rollBack();

            dd('StaffListController.php -> destroy : ' . $ex->getMessage());
        }

    }

    public function upload(Request $request)
    {            
        set_time_limit(0);

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);
         
        if ($validator->fails()) {
            return response()->json(['status' => "false", 'message' => $validator->errors()->first()]);
        }
       
        $file = $request->file('file');
                 
        try
        {
            $data = Excel::import(new StaffListImport(), $file);
        }
        catch (\Exception $e) {
             return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
      
       /* DB::beginTransaction();

        try {

            Excel::import(new DepartmentImport, $file);
            Excel::import(new UnitImport(), $file);
            Excel::import(new StaffListImport(), $file);
            Excel::import(new SupervisorImport(), $file);

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Staff list uploaded successfully']);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json(['status' => false, 'message' => $e->getMessage()]);//'An error occured while uploading the staff list. Please contact IT']);
        }*/

    }

    public function filter(Request $request)
    {
        $filters = [
            'name' => $request->name,
            'department' => $request->department,
            'unit' => $request->unit,
            'supervisor' => $request->supervisor,
        ];

        $staff = StaffList::filter($filters);

        return response()->json($staff);
    }
}
