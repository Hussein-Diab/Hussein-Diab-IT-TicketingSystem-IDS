<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketComment extends Model
{
    protected $table      = 'TicketComments';
    protected $primaryKey = 'Id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    protected $fillable = [
        'TicketId',
        'UserId',
        'Body'
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'UserId',
            'Id'
        );
    }
}