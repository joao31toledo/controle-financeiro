<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    protected $casts = [
        'payload' => 'array',
    ];
    
    protected $fillable = [
        'texto',
        'pacote',
        'titulo',
        'data_notificacao',
        'payload',
        'status'
    ];
}
