<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horariosdisponibles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicos_id')->constrained('medicos')->onDelete('cascade');
            $table->enum('diaSemana', ['L', 'Mar', 'Mie', 'J', 'V', 'S']);
            $table->time('horaInicio');
            $table->time('horaFin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horariosdisponibles');
    }
};
