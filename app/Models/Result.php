<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'result_date',
        'pdf_path',
        'validated_by',
        'validated_date',
        'status'
    ];
    public function orden()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function notificaciones()
    {
        return $this->hasMany(Notification::class);
    }

    public function getUrlPdfAttribute()
    {
        return $this->pdf_path
            ? asset('storage/'.$this->pdf_path)
            : null;
    }
    // Relación para saber quién validó el resultado
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
