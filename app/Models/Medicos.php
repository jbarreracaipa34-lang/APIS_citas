<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicos extends Model
{
    protected $fillable = ['nombre', 'apellido', 'numeroLicencia', 'telefono', 'email', 'especialidad_id'];

    public function especialidad()
    {
        return $this->belongsTo(Especialidades::class, 'especialidad_id');
    }

    public function horarios()
    {
        return $this->hasMany(HorariosDisponibles::class, 'medicos_id');
    }

    public function citas()
    {
        return $this->hasMany(Citas::class, 'medicos_id');
    }
}
