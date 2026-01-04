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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');

            // CI: guardar normalizado (ej: 12345678 o 12345678-1B)
            $table->string('ci', 20)->unique();

            $table->date('birth_date')->nullable();

            $table->string('phone', 30)->nullable();

            // Email opcional pero único cuando existe (Postgres permite varios NULL en UNIQUE)
            $table->string('email')->nullable()->unique();

            $table->string('address')->nullable();

            $table->unsignedSmallInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
