<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proforma extends Model
{
    use HasFactory;
    protected $fillable = [
        'paciente_id',
        'issue_date',
        'total_amount',
        'status'
    ];
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function detalles()
    {
        return $this->hasMany(ProformaDetail::class);
    }

    public function orden()
    {
        return $this->hasOne(Orders::class);
    }
}
