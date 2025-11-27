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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->dateTime('result_date')->nullable();
            $table->string('pdf_path')->nullable(); // storage path del pdf
            $table->unsignedBigInteger('validated_by')->nullable(); // user_id
            $table->dateTime('validated_date')->nullable();
            $table->string('status', 20)->default('pendiente'); 
            // pendiente | validado | enviado
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
