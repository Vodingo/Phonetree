<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\StaffList;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
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

    public function index()
    {
        return view('settings.users');
    }

    public function registered()
    {
        try {
            $users = User::where('active', true)->orderBy('name', 'asc')->get();

            $result = [];

            foreach ($users as $user) {

                $userRoles = $user->roles();

                $roleId = null;
                $roleName = null;

                if ($userRoles->count() > 0) {
                    $roleId = $userRoles->first()->id;
                    $roleName = $userRoles->first()->name;
                }

                $result[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => ['id' => $roleId, 'name' => $roleName],
                ];
            }

            return response()->json($result);
        } catch (\Exception $ex) {

            dd('UserController.php -> registered : ' . $ex->getMessage());

        }

    }

    public function unregistered()
    {
        try {
            $users = StaffList::where('active', true)->orderBy('first_name', 'asc')->get();

            $result = [];

            foreach ($users as $user) {
                $result[] = ['id' => $user->id, 'name' => $user->full_name];
            }

            return response()->json($result);
        } catch (\Exception $ex) {

            dd('UserController.php -> unregistered : ' . $ex->getMessage());

        }

    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'email' => Rule::unique('users')->where(function ($query) {
                return $query->where('active', 1);
            }),
            'username' => ['string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $request->name,
                'username' => strtolower($request->username),
                'email' => strtolower($request->email),
                'password' => Hash::make($request->password),
                'staff_id' => (int) $request->id,
            ]);

            $user->roles()->attach($request->role);

            DB::commit();

            return response()->json(['status' => true, 'message' => 'User added successfully']);

        } catch (\Exception $ex) {

            DB::rollBack();

            return response()->json(['status' => true, 'message' => 'An error occured while saving user data. Please contact IT' . $ex->getMessage()]);
        }

    }

    public function deactivate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user' => ['required'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            $user = User::find($request->user);
            $user->active = false;
            $user->save();

            return response()->json(['status' => true, 'message' => '']);
        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to deactivate user.Please contact IT' . $ex->getMessage()]);

        }

    }

    public function update(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255'],
                'username' => ['string', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            if ($request->updatepassword != 'false') {
                $validator = Validator::make($request->all(), [
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
            }

            $user = User::find($request->id);

            $user->name = $request->name;
            $user->username = strtolower($request->username);
            $user->email = strtolower($request->email);

            if ($request->updatepassword != 'false') {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $userRoles = $user->roles();

            $roleId = ($userRoles->count() > 0) ? $userRoles->first()->id : 0;

            if ($roleId != $request->role) {
                $userRoles->updateExistingPivot($roleId, ['active' => false]);
                $userRoles->attach($request->role);
            }

            return response()->json(['status' => true, 'message' => 'User data updated successfully.']);
        } catch (\Exception $ex) {

            return response()->json(['status' => false, 'message' => 'An error occured while trying to update user data . Please contact IT. Error : ' . $ex]);

        }

    }

    public function profile()
    {
        try {
            $user = User::find(auth()->user()->id);
            $userData = StaffList::find($user->staff_id);

            return view('settings.profile', ['profile' => $userData, 'user' => $user]);
        } catch (\Exception $ex) {

            dd('UserController.php -> profile : ' . $ex->getMessage());

        }

    }

    public function updateProfile(Request $request, StaffList $staffList)
    {
        try {
            if ($request->updatepassword != 'false') {
                $request->validate([
                    'id' => ['required'],
                    'first_name' => ['required', 'string', 'max:255'],
                    'last_name' => ['required', 'string', 'max:255'],
                    'other_name' => ['nullable', 'string', 'max:255'],
                    'work_phone' => ['nullable', 'string', 'max:255'],
                    'personal_phone' => ['nullable', 'string', 'max:255'],
                    'secondary_phone' => ['nullable', 'string', 'max:255'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);
            } else {
                $request->validate([
                    'id' => ['required'],
                    'first_name' => ['required', 'string', 'max:255'],
                    'last_name' => ['required', 'string', 'max:255'],
                    'other_name' => ['nullable', 'string', 'max:255'],
                    'work_phone' => ['nullable', 'string', 'max:255'],
                    'personal_phone' => ['nullable', 'string', 'max:255'],
                    'secondary_phone' => ['nullable', 'string', 'max:255'],
                ]);
            }

            $staff = StaffList::find($request->id);
            $staff->first_name = $request->first_name;
            $staff->last_name = $request->last_name;
            $staff->other_name = $request->other_name;
            $staff->work_phone = $request->work_phone;
            $staff->personal_phone = $request->personal_phone;
            $staff->secondary_phone = $request->secondary_phone;
            $staff->save();

            if ($request->updatepassword != 'false') {

                $findUser = User::where('staff_id', $request->id)->where('active', true)->get();

                if ($findUser->count() > 0) {
                    $user = $findUser->first();
                    $user->password = Hash::make($request->password);
                    $user->updated_at = Carbon::now();
                    $user->save();
                }
            }

            return redirect()->back()->with('status', 'Profile updated successfully');
        } catch (\Exception $ex) {

            dd('UserController.php -> updateProfile : ' . $ex->getMessage());

        }

    }

}
