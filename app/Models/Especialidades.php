<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidades extends Model
{
    protected $table = 'especialidades'; 

    protected $fillable = ['nombre', 'descripcion'];

    public function medicos()
    {
        return $this->hasMany(Medicos::class, 'especialidad_id');
    }
}
