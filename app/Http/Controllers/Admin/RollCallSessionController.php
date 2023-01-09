<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\RollCallSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RollCallSessionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth' /*, 'roles'*/]);
    }

    public function view()
    {
        return view('sessions.index');
    }

    public function getAllSessions()
    {
        try {
            $sessions = RollCallSession::getAllSessions();

            return response()->json($sessions);
        } catch (\Exception $ex) {
            dd('RollCallSessionController.php -> getAllSessions : ' . $ex->getMessage());
        }

    }

    public function filterSessions(Request $request)
    {
        try {
            $filters = [
                'description' => $request->description,
                'filterByDate' => $request->filterByDates,
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
            ];

            $sessions = RollCallSession::filterSessions($filters);

            return response()->json($sessions);
        } catch (\Exception $ex) {
            dd('RollCallSessionController.php -> filterSessions : ' . $ex->getMessage());
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $rollCallSession = new RollCallSession();
            $sessions = $rollCallSession->active();

            return response()->json($sessions);
        } catch (\Exception $ex) {
            dd('RollCallSessionController.php -> index : ' . $ex->getMessage());
        }

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

        try {

            $validator = Validator::make($request->all(), [
                'date' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            $session = new RollCallSession();
            $session->incident_date = $request->date;
            $session->description = $request->description;
            $session->active = '1';
            $session->created_by = auth()->user()->id;
            $session->save();

            return response()->json(['status' => true, 'message' => 'Session created successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to create the roll call session ' . $ex->getMessage()]);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RollCallSession  $rollCallSession
     * @return \Illuminate\Http\Response
     */
    public function show(RollCallSession $rollCallSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RollCallSession  $rollCallSession
     * @return \Illuminate\Http\Response
     */
    public function edit(RollCallSession $rollCallSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RollCallSession  $rollCallSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RollCallSession $rollCallSession)
    {
        try {

            $validator = Validator::make($request->all(), [
                'id' => ['required', 'integer'],
                'date' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            $rollCallSession = RollCallSession::findOrFail($request->id);
            $rollCallSession->incident_date = $request->date;
            $rollCallSession->description = $request->description;
            $rollCallSession->updated_at = Carbon::now();
            $rollCallSession->updated_by = auth()->user()->id;
            $rollCallSession->save();

            return response()->json(['status' => true, 'message' => 'Session successfully completed']);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => 'An error occured while trying to complete the session' . $ex->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $rollCallSession = RollCallSession::findOrFail($request->session);
            $rollCallSession->deleted = '1';
            $rollCallSession->deleted_at = Carbon::now();
            $rollCallSession->deleted_by = auth()->user()->id;
            $rollCallSession->save();

            return response()->json(['status' => true, 'message' => 'Session successfully deleted']);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => 'An error occured while trying to delete the session' . $ex->getMessage()]);
        }
    }

    public function complete(Request $request)
    {
        try {

            $rollCallSession = RollCallSession::findOrFail($request->session);
            $rollCallSession->active = '0';
            $rollCallSession->updated_at = Carbon::now();
            $rollCallSession->updated_by = auth()->user()->id;
            $rollCallSession->save();

            return response()->json(['status' => true, 'message' => 'Session successfully completed']);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => 'An error occured while trying to complete the session' . $ex->getMessage()]);
        }
    }
}
