<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'codigo_de_orden',
        'fecha_creacion',
        'distribution_id'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime'
    ];

    // relacion n:1 con la tabla clients (una orden pertenece a un cliente)
    public function client() 
    {
        return $this->belongsTo(Client::class);
    }

    // relacion n:1 con la tabla distributions (una orden se asigna a un reparto)
    public function distribution() 
    {
        return $this->belongsTo(Distribution::class);
    }
}
