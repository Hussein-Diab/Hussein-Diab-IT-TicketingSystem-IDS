<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'Categories';
    protected $primaryKey = 'id';
    protected $fillable = ['Name'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'CategoryId', 'Id');
    }
}
