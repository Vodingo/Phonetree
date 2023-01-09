<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function view()
    {
        return view('roles.index');
    }

    public function index()
    {
        try {
            $roles = Role::where('active', '1')->orderBy('name', 'ASC')->get(['id', 'name']);

            return response()->json($roles);

        } catch (\Exception $ex) {
            dd('RoleController.php -> getUnaccountedStaff : ' . $ex->getMessage());
        }

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

            $permission = Role::where(['name' => $request->name]);

            if ($permission->count() > 0) {
                return response()->json(['status' => false, 'message' => 'The role ' . $request->name . ' already exists']);
            }

            Role::create([
                'name' => $request->name,
            ]);

            return response()->json(['status' => true, 'message' => 'Role added successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to create role ' . $ex->getMessage()]);

        }

    }

    public function update(Request $request, Role $role)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            $role->name = $request->name;
            $role->save();

            return response()->json(['status' => true, 'message' => 'Role updated successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to update role ' . $ex->getMessage()]);

        }

    }

    public function destroy(Request $request, Role $role)
    {
        try {
            $role->active = '0';
            $role->save();

            return response()->json(['status' => true, 'message' => 'Role deleted successfully']);

        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to delete role ' . $ex->getMessage()]);

        }

    }

    public function permissions(Request $request, Role $role)
    {
        try {
            return response()->json($role->getPermissions($role->id));

        } catch (\Exception $ex) {
            dd('RoleController.php -> permissions : ' . $ex->getMessage());
        }

    }

    public function assignedPermissions(Request $request, Role $role)
    {
        try {
            return response()->json($role->assignedPermissions($role->id));

        } catch (\Exception $ex) {
            dd('RoleController.php -> assignedPermissions : ' . $ex->getMessage());
        }

    }

    public function assignPermissions(Request $request, Role $role)
    {
        try
        {
            $permissions = isset($request->assignedPermissions) ? $request->assignedPermissions : [];

            $role->permissions()
                ->newPivotStatement()
                ->where('role_id', '=', $role->id)
                ->whereNotIn('permission_id', $permissions)
                ->update(['active' => '0']);

            $role->permissions()
                ->newPivotStatement()
                ->where('role_id', '=', $role->id)
                ->whereIn('permission_id', $permissions)
                ->update(['active' => '1']);

            $role->permissions()->syncWithoutDetaching($permissions);

            return response()->json(['status' => true, 'message' => 'Permissions assigned to the role successfully']);
        } catch (\Exception $ex) {
            return response()->json(['status' => false, 'message' => 'An error occured while trying to assign permissions to the role', 'error' => $ex->getMessage()]);
        }

    }
}
