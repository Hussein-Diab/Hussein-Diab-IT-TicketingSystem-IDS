<?php 

namespace App\Models; 

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Tymon\JWTAuth\Contracts\JWTSubject; // <-- 1. Added missing import

class User extends Authenticatable implements JWTSubject // <-- 2. Added interface implementation
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



    /**
     * Tells JWT which field is the user's unique ID
     */
    public function getJWTIdentifier() 
    { 
        return $this->getKey(); // returns user id (in your case, 'Id')
    } 

    /**
     * Extra data to put inside the token (we leave empty)
     */
    public function getJWTCustomClaims() 
    { 
        return []; 
    }
} 
