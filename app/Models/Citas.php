<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Citas extends Model
{
    protected $fillable = ['pacientes_id', 'medicos_id', 'fechaCita', 'horaCita', 'estado', 'observaciones'];

    public function paciente()
    {
        return $this->belongsTo(Pacientes::class, 'pacientes_id');
    }

    public function medico()
    {
        return $this->belongsTo(Medicos::class, 'medicos_id');
    }
}
