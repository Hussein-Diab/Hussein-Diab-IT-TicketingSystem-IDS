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
} 
