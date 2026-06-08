<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Priority extends Model
{
    protected $table = 'Priorities';
    protected $primaryKey = 'Id';
    protected $fillable = ['Name'];
}
