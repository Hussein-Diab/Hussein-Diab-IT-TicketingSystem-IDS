<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    protected $table = 'Users';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'Name',
        'Email',
        'Password',
        'RoleId'
    ];

    protected $hidden = [
        'Password',
    ];

    public function getAuthPassword()
    {
        return $this->Password;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function isAdmin()
    {
        return $this->RoleId == 1;
    }

    public function isAgent()
    {
        return $this->RoleId == 2;
    }

    public function isEmployee()
    {
        return $this->RoleId == 3;
    }

    public function isManager()
    {
        return $this->RoleId == 4;
    }
    public function isAdminOrManager()
    {
        return in_array($this->RoleId, [1, 4]);
    }
}
