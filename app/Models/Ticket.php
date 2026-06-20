<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'Tickets';
    protected $primaryKey = 'Id';
    protected $keyType    = 'int';
    public $incrementing  = true;
    protected $fillable = [
        'RefNumber',
        'Title',
        'Description',
        'UserId',
        'AssignedTo',
        'CategoryId',
        'PriorityId',
        'StatusId',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'Id');
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class, 'PriorityId', 'Id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'StatusId', 'Id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'AssignedTo', 'Id');
    }
    public function comments()
    {
        return $this->hasMany(
            TicketComment::class,
            'TicketId',
            'Id'
        )->orderBy('created_at', 'asc');
    }
}
