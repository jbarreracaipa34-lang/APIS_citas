<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pacientes_id')->constrained('pacientes')->onDelete('cascade');
            $table->foreignId('medicos_id')->constrained('medicos')->onDelete('cascade');
            $table->date('fechaCita');
            $table->time('horaCita');
            $table->enum('estado', ['pendiente', 'completada', 'cancelada'])->default('pendiente');
            $table->string('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
