<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    protected $fillable = ['name'];

    public function permissions()
    {  
        //KLM
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id')->wherePivot('active', '1');
    }

    public function getPermissions($role)
    {
        $permissions = DB::select(
                            DB::raw('SELECT p.id, p.description, CASE WHEN permission_id IS NOT NULL THEN true ELSE false END AS assigned
                                    FROM permissions p  
                                    INNER JOIN 
                                    (
                                        SELECT * FROM role_permission
                                        WHERE active = true AND role_id = :roleId
                                    ) role_permissions ON p.id = permission_id 
                                    WHERE p.active = true
                                    ORDER BY p.description ASC'),
                            ['roleId' => $role]
                        );

        return $permissions;
    }

    public function assignedPermissions($role)
    {
        $permissions = DB::select(
                            DB::raw('SELECT p.id, p.description, CASE WHEN permission_id IS NOT NULL THEN true ELSE false END AS assigned
                                    FROM permissions p  
                                    LEFT JOIN 
                                    (
                                        SELECT * FROM role_permission
                                        WHERE active = true AND role_id = :roleId
                                    ) role_permissions ON p.id = permission_id 
                                    WHERE p.active = true
                                    ORDER BY p.description ASC'),
                            ['roleId' => $role]
                        );

        return $permissions;
    }
}
