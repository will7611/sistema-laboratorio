<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Paciente extends Model
{
    protected $fillable = [
        'name',
        'last_name',
        'ci',
        'birth_date',
        'phone',
        'email',
        'address',
        'status',
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
    protected $casts = [
        'birth_date' => 'date',
        'status' => 'integer',
    ];

    // Edad calculada (no se guarda en DB)
    public function getAgeAttribute()
    {
        return $this->birth_date ? Carbon::parse($this->birth_date)->age : null;
    }

}
