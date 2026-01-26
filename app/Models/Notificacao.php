<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';
    
    protected $guarded = [];
    
    protected $casts = [
        'payload' => 'array',
    ];
}
