<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    use HasFactory;

    protected $fillable = [
        'valor',
        'loja',
        'cartao',
        'status',
        'data_compra',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_compra' => 'datetime',
    ];
}

