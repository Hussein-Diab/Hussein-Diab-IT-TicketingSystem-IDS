<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $table      = 'ticketattachments';
    protected $primaryKey = 'Id';
    protected $keyType    = 'int';
    public $incrementing  = true;

    protected $fillable = [
        'TicketId',
        'FileName',
        'FilePath',
        'FileSize',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId', 'Id');
    }
}