<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'ci',
        'phone',
        'birth_date',
        'age',
        'email',
        'address',
        'status'
    ];
    public function proformas()
    {
        return $this->hasMany(Proforma::class);
    }

    public function ordenes()
    {
        return $this->hasMany(Orders::class);
    }

    public function getNombreCompletoAttribute()
    {
        return "{$this->name} {$this->last_name}";
    }

}
