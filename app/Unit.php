<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    protected $hidden = ['created_by', 'updated_by', 'created_at', 'updated_at', 'active'];

    protected $fillable = ['name', 'created_by', 'active'];
    
    public function staff()
    {
        return $this->hasMany(StaffList::class, 'unit_staff', 'unit_id', 'staff_id');
    }
}
