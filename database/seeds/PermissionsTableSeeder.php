<?php

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_permission')->truncate();
        Permission::truncate();
        
        $permission_ids = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            
            $action = $route->getActionName();

            $action_parts = explode('@', $action);

            $controller = $action_parts[0];
            $method = end($action_parts);

            $permission_check = Permission::where(['controller' => $controller, 'method' => $method])->first();

            if (!$permission_check) {
                $permission = new Permission();
                $permission->name = str_replace('Controller', '', $controller) . ' '. $method;
                $permission->controller = $controller;
                $permission->method = $method;
                $permission->save();

                $permission_ids[] = $permission->id;
            } else {
                $permission_ids[] = $permission_check->id;
            }
        }

        $admin_role = Role::where('name', 'Admin')->first();

        $admin_role->permissions()->attach($permission_ids);

        $defaultUser = User::find(1);
        $defaultUser->roles()->attach($admin_role);
    }
}
