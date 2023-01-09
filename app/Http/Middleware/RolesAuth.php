<?php

namespace App\Http\Middleware;

use App\Role;
use Closure;

class RolesAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      
        $permissions = auth()->user()->roles()->first()->permissions;         

        if ($permissions->count() > 0) {
            
            $actionName = class_basename($request->route()->getActionName());
    
            foreach ($permissions as $permission) {
                $controller_parts  = explode('\\', $permission->controller);
                $controller = end($controller_parts);
    
                if ($actionName == $controller .'@'. $permission->method) {
                    return $next($request);
                }
    
            }
        }
        
        if ($request->ajax()) {
            return response()->json(['error' => false, 'message' => 'You dont have enought rights to complete the request. Please contact IT']);
        }

        return redirect('/access-denied');
    }
}
