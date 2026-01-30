<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Despesa;

class DespesaController extends Controller
{
    public function index()
    {
        $despesas = Despesa::orderBy('created_at', 'desc')->get();

        return view('despesas.index', compact('despesas'));
    }
}
