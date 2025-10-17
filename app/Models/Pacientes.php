<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Pacientes extends Model
{
    use HasApiTokens, Notifiable;
    
    protected $fillable = ['nombre', 'apellido', 'tipoDocumento', 'numeroDocumento', 'fechaNacimiento', 'genero', 'telefono', 'email', 'direccion','eps', 'password'];

    public function citas()
    {
        return $this->hasMany(Citas::class, 'pacientes_id');
    }
}
