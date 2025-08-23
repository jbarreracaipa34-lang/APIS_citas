<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellido');
            $table->enum('tipoDocumento', ['CC', 'TI', 'CE']);
            $table->string('numeroDocumento')->unique();
            $table->date('fechaNacimiento');
            $table->enum('genero', ['M', 'F']);
            $table->string('telefono')->nullable();
            $table->string('email')->unique();
            $table->string('direccion')->nullable();
            $table->string('eps');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
