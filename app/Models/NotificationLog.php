<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'result_id', 'patient_name', 'patient_phone', 
        'patient_email', 'status', 'platform', 'n8n_message'
    ];

    public function result()
    {
        return $this->belongsTo(Result::class);
    }
}
