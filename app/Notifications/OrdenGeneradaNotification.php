<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

// El "implements ShouldBroadcastNow" es vital para el tiempo real
class OrdenGeneradaNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $proforma_id;
    public $paciente_nombre;

    public function __construct($proforma_id, $paciente_nombre)
    {
        $this->proforma_id = $proforma_id;
        $this->paciente_nombre = $paciente_nombre;
    }

    // Le decimos a Laravel que guarde en BD y mande por Reverb
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // 1. CÓMO SE GUARDARÁ EN LA BASE DE DATOS
    public function toArray($notifiable)
    {
        return [
            'proforma_id' => $this->proforma_id,
            'paciente_nombre' => $this->paciente_nombre,
            'mensaje' => 'Se generó la orden #' . $this->proforma_id . ' para el paciente ' . $this->paciente_nombre,
        ];
    }

    // 2. CÓMO SE ENVIARÁ POR REVERB
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'proforma_id' => $this->proforma_id,
            'paciente_nombre' => $this->paciente_nombre,
        ]);
    }
}
