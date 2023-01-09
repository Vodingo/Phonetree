<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $hidden = ['created_by', 'updated_by', 'created_at', 'updated_at', 'active'];

    protected $fillable = ['name', 'created_by', 'active'];
    
    public function staff()
    {
        return $this->hasMany(StaffList::class, 'department_staff', 'staff_id', 'department_id');
    }
}
