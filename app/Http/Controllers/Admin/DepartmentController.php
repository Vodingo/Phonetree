<?php

namespace App\Http\Controllers\Admin;

use App\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $departments = Department::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']);
            return response()->json($departments);

        } catch (\Exception $ex) {
        
            dd('DepartmentController.php -> index : ' .$ex->getMessage());
        }
        
        
    }

    /**
     * Return the departments view.
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        return view('departments.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255']
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }
    
            Department::create([
                'name' => $request->name,
                'created_by' => Auth::user()->id
            ]);
    
            return response()->json(['status' => true, 'message' => 'Department created successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to create department ' . $ex->getMessage()]);
          
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return response()->json($department);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        return response()->json($department);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255']
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()]);
            }
    
            $department->name = $request->name;
            $department->updated_by = Auth::user()->id;
            $department->updated_at = Carbon::now();
            $department->save();
    
            return response()->json(['status' => true, 'message' => 'Department updated successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to update department ' . $ex->getMessage()]);
          
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        try {
            $department->active = '0';
            $department->updated_by = Auth::user()->id;
            $department->updated_at = Carbon::now();
            $department->save();
    
            return response()->json(['status' => "true", 'message' => 'Department deleted successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => "false", 'message' => 'An error occured while trying to "delete" department ' . $ex->getMessage()]);
          
        }
       
    }
}
