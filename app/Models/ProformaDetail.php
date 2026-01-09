<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProformaDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'proforma_id',
        'analysis_id',
        'amount',
        'unit_price',
        'subtotal'
    ];
    public function proforma()
    {
        return $this->belongsTo(Proforma::class);
    }

    public function analisis()
    {
        return $this->belongsTo(Analysis::class, 'analysis_id');
    }
}
