<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
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
    public function index($session)
    {
        $user = auth()->user()->id;

        $entries['data'] = Register::entries($session, $supervisor = 1);

        return response()->json($entries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

            $exisitingRecord = Register::where('staff_id', $staff)
                ->where('session_id', $session)
                ->where('active', true)
                ->get();

            if ($exisitingRecord) {
                $exisitingRecord->active = false;
                $exisitingRecord->updated_by = Auth::user()->id;
                $exisitingRecord->save();
            }

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

    public function storeEntry(Request $request)
    {
        $session = $request->session;
        $staffId = $request->staff;
        $accounted = $request->accounted;
        $comments = $request->comments;

        if (!isset($session)) {
            return response()->json(['status' => false, 'message' => 'Please select the roll call session']);
        }

        Register::where('staff_id', $staffId)
            ->where('session_id', $session)
            ->where('active', true)
            ->update(['active' => false, 'updated_by' => Auth::user()->id]);

        $register = new Register();
        $register->session_id = $session;
        $register->staff_id = $staffId;

        if (isset($accounted)) {
            $register->status_id = $accounted;
        }

        $register->comments = $comments;
        $register->active = true;
        $register->created_by = Auth::user()->id;
        $register->save();


        return  response()->json(['status' => true, 'message' => 'Register updated successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
