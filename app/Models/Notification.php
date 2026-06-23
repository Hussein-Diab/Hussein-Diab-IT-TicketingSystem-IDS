<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table      = 'notifications';
    protected $primaryKey = 'Id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    protected $fillable = [
        'UserId',
        'Message',
        'IsRead'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }
}