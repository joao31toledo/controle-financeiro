<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notificacao;

class NotificacaoController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pacote' => 'required|string',
            'texto' => 'required|string',
            'data' => 'nullable|date',
            'titulo' => 'nullable|string',
        ]);

        $notificacao = Notificacao::firstOrCreate([
            // criterios para definir se é uma notificacao repetida:
            'pacote' => $validated['pacote'],
            'texto' => $validated['texto'],
            'data_notificacao' => $request->input('data'),
        ],
        [
            'titulo' => $request->input('titulo'),
            'payload' => $request->all(), // o JSON original completo
            'status' => 'pendente' // toda notificacao é criada como 'pendente' até ser tratada em despesa
        ]);

        return response()->json([
            'message' => 'Notificacao recebida com sucesso',
            'id'=> $notificacao->id,
        ], 200);
    }
}
