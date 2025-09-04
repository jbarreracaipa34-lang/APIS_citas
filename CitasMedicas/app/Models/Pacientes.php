<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pacientes extends Model
{
    protected $fillable = ['user_id','nombre', 'apellido', 'tipoDocumento', 'numeroDocumento', 'fechaNacimiento', 'genero', 'telefono', 'email', 'direccion','eps'];

    public function citas()
    {
        return $this->hasMany(Citas::class, 'pacientes_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
