<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Despesa;

class DespesaController extends Controller
{
    public function index()
    {
        $despesas = Despesa::orderBy('data_compra', 'desc')->get();

        return view('despesas.index', compact('despesas'));
    }

    public function create()
    {
        return view ('despesas.create');
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'loja' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'data_compra' => 'required|date',
            'cartao' => 'string|max:255',
        ]);
        
        $dadosValidados['status'] = 'verificado';
        
        Despesa::create($dadosValidados);
        
        return redirect()->route('despesas.index')
                         ->with('success', 'Despesa cadastrada com sucesso!');
    }

    public function edit(Despesa $despesa)
    {
        return view ('despesas.edit', compact('despesa'));
    }

    public function update(Request $request, Despesa $despesa)
    {
        $dados = $request->validate([
            'loja' => 'required|string|max:255',
            'valor' => 'required|numeric',
            'data_compra' => 'required|date',
            'cartao' => 'required|string|max:255',
        ]);

        $despesa->update($dados);

        return redirect()->route('despesas.index')
                         ->with('success', 'Despesa atualizada com sucesso!');
    }

    public function destroy(Despesa $despesa)
    {
        $despesa->delete();

        return redirect()->route('despesas.index')
                         ->with('success', 'Despesa removida com sucesso!');
    }
}
