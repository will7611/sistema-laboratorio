<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            // Relación con el resultado médico
        $table->foreignId('result_id')->constrained('results')->onDelete('cascade');
        
        // Datos del Paciente (Snapshot del momento del envío)
        $table->string('patient_name');
        $table->string('patient_phone');
        $table->string('patient_email')->nullable();
        
        // Datos de la transacción con n8n
        $table->string('status'); // 'EXITOSO' o 'FALLIDO'
        $table->string('platform'); // 'n8n-whatsapp-email'
        $table->text('n8n_message')->nullable(); // El mensaje de respuesta "OK"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
