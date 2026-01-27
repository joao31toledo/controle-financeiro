<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\NotificacaoController;

use App\Http\Controllers\DespesaController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/despesas', [DespesaController::class, 'index'])->name('despesas.index');