<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'patente',
        'modelo'
    ];

    // relacion 1:n con la tabla distributions (un vehiculo puede tener muchos repartos)
    public function distributions() 
    {
        return $this->hasMany(Distribution::class);
    }

    // relacion 1:n con la tabla distributions
    public function repartos()
    {
        return $this->hasMany(Distribution::class, 'vehicle_id');
    }
}
