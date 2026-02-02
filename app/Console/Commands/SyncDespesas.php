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
    protected $description = 'Sincroniza notificações do Sheets para o Banco de Dados';

    public function handle(DespesaService $despesaService)
    {
        $this->info('Iniciando sincronização...');

        $spreadsheetId = env('GOOGLE_SPREADSHEET_ID');
        $sheetName = 'notificacoes';

        $rows = Sheets::spreadsheet($spreadsheetId)->sheet($sheetName)->get();

        if ($rows->count() <= 1) {
            $this->info('Nenhuma notificação nova.');
            return;
        }

        // Remove o cabeçalho
        $header = $rows->pull(0); 
        $count = 0;

        foreach ($rows as $index => $row) {
            $pacote = $row[1] ?? 'desconhecido';
            $texto = $row[2] ?? '';
            $dataRaw = $row[3] ?? $row[0] ?? null;
            $titulo = $row[4] ?? '';

            if (empty($texto)) continue;

            try {
                if (is_numeric($dataRaw)) {
                    // Se for Timestamp (Macrodroid), cria direto (UTC)
                    $dataNotificacao = Carbon::createFromTimestamp((int)$dataRaw);
                } elseif ($dataRaw && str_contains($dataRaw, '/')) {
                    // Se for Texto BR (30/01/2026), cria respeitando formato
                    $dataNotificacao = Carbon::createFromFormat('d/m/Y H:i:s', $dataRaw);
                } else {
                    // Tenta parsing padrão
                    $dataNotificacao = Carbon::parse($dataRaw);
                }
            } catch (\Throwable $e) {
                // Se falhar, usa data atual
                $dataNotificacao = now();
            }

            $this->comment(" > Processando: $texto"); 

            // Salva no banco (Laravel converte pra UTC automaticamente se precisar)
            $notificacao = Notificacao::firstOrCreate(
                [
                    'texto' => $texto, 
                    'data_notificacao' => $dataNotificacao 
                ],
                [
                    'pacote' => $pacote,
                    'titulo' => $titulo,
                    'payload' => ['origem' => 'google_sheets', 'data_original' => $dataRaw],
                    'status' => 'pendente'
                ]
            );

            if ($notificacao->wasRecentlyCreated || $notificacao->status === 'pendente') {
                $despesaService->processar($notificacao);
                $count++;
            }
        }

        // Limpa a planilha após processar
        Sheets::spreadsheet($spreadsheetId)->sheet($sheetName)->clear();

        $this->info("✅ Sucesso! $count notificações processadas e planilha limpa.");
    }
}