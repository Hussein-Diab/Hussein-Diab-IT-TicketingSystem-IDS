<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'Statuses';
    protected $primaryKey = 'Id';
    protected $fillable = ['Name'];
}
