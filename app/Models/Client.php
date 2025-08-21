<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'razon_social',
        'email',
        'direccion',
        'latitud',
        'longitud'
    ];

    // relacion 1:n con la tabla orders (una cliente puede tener muchas ordenes)
    public function orders() 
    {
        return $this->hasMany(Order::class);
    }
}
