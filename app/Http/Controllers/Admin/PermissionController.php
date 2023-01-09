<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function view()
    {
        return view('permissions.index');
    }

    public function index()
    {
        try {

            $permissions = Permission::where('active', '1')->orderBy('description', 'ASC')->get(['id', 'key', 'description']);
            return response()->json($permissions);

        } catch (\Exception $ex) {
            dd('PermissionController.php -> index : ' . $ex->getMessage());
        }

    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            $permission = Permission::where(['key' => $request->name, 'active' => true]);

            if ($permission->count() > 0) {
                return response()->json(['status' => false, 'message' => 'Permission for the selected route already exists']);
            }

            Permission::create([
                'key' => $request->name,
                'description' => $request->description,
                'active' => "true",
            ]);

            return response()->json(['status' => true, 'message' => 'Permission added successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to add permission ' . $ex->getMessage()]);
        }

    }

    public function update(Request $request, Permission $permission)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            $permission->key = $request->name;
            $permission->description = $request->description;
            $permission->save();

            return response()->json(['status' => true, 'message' => 'Permission updated successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to update permission ' . $ex->getMessage()]);

        }

    }

    public function destroy(Request $request, Permission $permission)
    {
        try {

            $permission->active = '0';
            $permission->save();

            return response()->json(['status' => true, 'message' => 'Permission deleted successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to delete permission ' . $ex->getMessage()]);

        }

    }
}
