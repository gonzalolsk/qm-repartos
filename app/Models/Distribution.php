<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_de_reparto',
        'fecha_entrega',
        'estado',
        'vehicle_id'
    ];

    // casteamos la fecha de entrega a date para que se guarde como fecha y no como datetime
    protected $casts = [
        'fecha_entrega' => 'date'
    ];

    // relacion 1:n con la tabla orders (un reparto puede tener muchas ordenes)
    public function orders() 
    {
        return $this->hasMany(Order::class);
    }

    // relacion n:1 con la tabla vehicles
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

}
