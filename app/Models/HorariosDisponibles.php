<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorariosDisponibles extends Model
{
    protected $table = 'horariosdisponibles';
    protected $fillable = ['medicos_id', 'diaSemana', 'horaInicio', 'horaFin'];

    public function medico()
    {
        return $this->belongsTo(Medicos::class, 'medico_id');
    }
}
