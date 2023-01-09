<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UnitController extends Controller
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
            $units = Unit::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']);
            return response()->json($units);

        } catch (\Exception $ex) {

            dd('UnitController.php -> index : ' .$ex->getMessage());

        }

    }

    /**
     * Return the units view.
     *
     * @return \Illuminate\Http\Response
     */
    public function view()
    {
        return view('units.index');
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            Unit::create([
                'name' => $request->name,
                'created_by' => Auth::user()->id,
            ]);

            return response()->json(['status' => true, 'message' => 'Unit created successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to create unit ' . $ex->getMessage()]);

        }

    }

    public function update(Request $request, Unit $unit)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()]);
            }

            $unit->name = $request->name;
            $unit->updated_by = Auth::user()->id;
            $unit->updated_at = Carbon::now();
            $unit->save();

            return response()->json(['status' => true, 'message' => 'Unit updated successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to update unit ' . $ex->getMessage()]);

        }

    }

    public function destroy(Unit $unit)
    {
        try {
            $unit->active = '0';
            $unit->updated_by = Auth::user()->id;
            $unit->updated_at = Carbon::now();
            $unit->save();

            return response()->json(['status' => true, 'message' => 'Unit deleted successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to update unit ' . $ex->getMessage()]);

        }

    }
}
