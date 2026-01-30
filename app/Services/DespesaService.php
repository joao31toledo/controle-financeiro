<?php

namespace App\Services;

use App\Models\Despesa;
use App\Models\Notificacao;

class DespesaService
{
    public function processar(Notificacao $notificacao): void
    {
        $dados = ParserService::analisar($notificacao->texto);

        if (!empty($dados)) {
            
            $dados['status'] = 'pendente';

            Despesa::create($dados);

            //Marca a notificação como processada para não ler de novo
            $notificacao->status = 'processado';
            $notificacao->save();
        }
    }
}