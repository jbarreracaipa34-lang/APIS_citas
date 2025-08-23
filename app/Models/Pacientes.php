<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
    protected $fillable = ['nombre', 'apellido', 'tipoDocumento', 'numeroDocumento', 'fechaNacimiento', 'genero', 'telefono', 'email', 'direccion','eps'];

    public function citas()
    {
        return $this->hasMany(Citas::class, 'pacientes_id');
    }
}
