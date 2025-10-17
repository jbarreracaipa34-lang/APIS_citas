<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Medicos extends Model
{
    use HasApiTokens;
    
    protected $fillable = ['nombre', 'apellido', 'numeroLicencia', 'telefono', 'email', 'especialidad_id', 'password'];

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
