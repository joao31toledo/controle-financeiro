<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Revolution\Google\Sheets\Facades\Sheets;
use App\Models\Notificacao;
use App\Services\DespesaService;
use Carbon\Carbon;

class SyncDespesas extends Command
{
    protected $signature = 'despesas:sync';
    protected $description = 'Busca notificações da Planilha e processa despesas';

    public function handle(DespesaService $despesaService)
    {
        $this->info('Iniciando sincronização com o Google Sheets...');

        $spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
        $sheetName = 'notificacoes';

        // 1. Ler os dados da planilha
        $rows = Sheets::spreadsheet($spreadsheetId)->sheet($sheetName)->get();

        // Se só tiver o cabeçalho (ou nem isso), para.
        if ($rows->count() <= 1) {
            $this->info('💤 Nenhuma notificação nova encontrada.');
            return;
        }

        // Pega o cabeçalho pra não processar ele
        $header = $rows->pull(0); 
        
        $this->info("Encontradas " . $rows->count() . " novas notificações. Processando...");

        $count = 0;

        foreach ($rows as $index => $row) {
            // O Google Forms as vezes manda colunas vazias, vamos garantir
            // A ordem deve ser: [0] => Data/Hora, [1] => Pacote, [2] => Texto
            // Ajuste os índices conforme a ordem das colunas na sua planilha!
            
            // Dica: Dê um dd($row) aqui na primeira vez se der erro pra ver a ordem
            $pacote = $row[1] ?? 'desconhecido';
            $texto = $row[2] ?? '';
            $dataHora = $row[3] ?? $row[0]; 

            if (empty($texto)) continue;

            $this->comment(" > Processando: $texto");

            // 2. Criar a Notificação no Banco Local (Backup)
            // Usamos firstOrCreate para evitar duplicação se rodar 2x sem querer
            $notificacao = Notificacao::firstOrCreate(
                [
                    'texto' => $texto, 
                    'data_notificacao' => Carbon::parse($dataHora) // Tenta converter a data do Google
                ],
                [
                    'pacote' => $pacote,
                    'titulo' => 'Importado via Sheets',
                    'payload' => ['origem' => 'google_sheets'],
                    'status' => 'pendente'
                ]
            );

            // 3. Chamar o Serviço para virar Despesa
            if ($notificacao->wasRecentlyCreated || $notificacao->status === 'pendente') {
                $despesaService->processar($notificacao);
                $count++;
            }
        }

        // 4. Limpar a planilha (Inbox Zero)
        Sheets::spreadsheet($spreadsheetId)->sheet($sheetName)->clear();
        
        // Recria o cabeçalho pra não ficar feio
        Sheets::spreadsheet($spreadsheetId)->sheet($sheetName)->append([$header]);

        $this->success("Sucesso! $count notificações processadas e planilha limpa.");
    }
}