<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysNotification extends Model
{
    protected $table = 'sys_notifications';

    protected $guarded = [];

    public $incrementing = false;
    protected $keyType = 'string';

    // ¡ESTO ES LO QUE FALTA! Le decimos que convierta el arreglo a JSON para PostgreSQL
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
}
