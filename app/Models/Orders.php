<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        'paciente_id',
        'proforma_id',
        'creation_date',
        'status'
    ];
     public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function resultado()
    {
        return $this->hasOne(Result::class, 'order_id', 'id');
    }
}
