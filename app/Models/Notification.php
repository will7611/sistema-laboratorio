<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'result_id',
        'channel',
        'send_date',
        'status',
        'message_error'
    ];
     public function resultado()
    {
        return $this->belongsTo(Result::class, 'result_id');
    }
}
