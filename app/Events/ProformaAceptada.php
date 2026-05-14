<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // <-- Muy importante
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProformaAceptada implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $proforma_id;
    public $paciente_nombre;

    // Recibimos los datos desde el controlador
    public function __construct($proforma_id, $paciente_nombre)
    {
        $this->proforma_id = $proforma_id;
        $this->paciente_nombre = $paciente_nombre;
    }

    // Definimos el canal por el que viaja la notificación
    public function broadcastOn(): array
    {
        return [
            new Channel('laboratorio-notificaciones'),
        ];
    }
}
