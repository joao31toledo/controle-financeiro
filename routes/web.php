<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\NotificacaoController;

Route::get('/', function () {
    return view('welcome');
});
