<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'work_phone_number', 'personal_phone_number', 'department', 'username', 'staff_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at', 'active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = ['details', 'roles'];

    public function details()
    {
        try {
            return $this->belongsTo(Supervisor::class, 'staff_id', 'staff_id');
        } catch (\Exception $ex) {
            dd('User.php -> details : ' .$ex->getMessage());
        }

    }

    public function roles()
    {
        try {
            return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id')->wherePivot('active', true);
        } catch (\Exception $ex) {
            dd('User.php -> roles : ' .$ex->getMessage());
        }

    }

    public function permissions()
    {
        return $this->roles->map->permissions->flatten()->pluck('key')->unique();
    }
}
