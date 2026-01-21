<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class WebhookReceptionTest extends TestCase
{
    // O RefreshDatabase é apaga todas as tabelas e cria de novo ANTES de cada teste rodar. Assim, um teste nunca suja o banco do outro.
    use RefreshDatabase;
    
    #[Test]
    public function deve_receber_webhook_valido_e_salvar_bruto()
    {
        // 1. PREPARAR
        // Criamos um dado falso que imita perfeitamente o que o celular enviaria.
        $payload = [
            'pacote' => 'com.nubank',
            'titulo' => 'Compra processada',
            'texto'  => 'Compra de R$ 20,00 no iFood',
            'data'   => '2026-01-20 15:00:00'
        ];

        // 2. AGIR
        // Fingimos ser o celular enviando um POST para a nossa rota /api/webhook
        $response = $this->postJson('/api/notificacoes', $payload);

        // 3. VERIFICAR
        
        // Verifica se o servidor respondeu "200 OK" (Sucesso)
        $response->assertStatus(200);

        // Verifica se, lá no banco de dados, na tabela 'notificacoes',
        // existe uma linha com esses dados específicos.
        $this->assertDatabaseHas('notificacoes', [
            'pacote' => 'com.nubank',
            'status' => 'pendente' // O status inicial obrigatório
        ]);
    }

    #[Test]
    public function deve_rejeitar_payload_incompleto()
    {
        // 1. PREPARAR
        // Enviamos um array vazio, sem os campos obrigatórios.
        $payloadRuim = [];

        // 2. AGIR
        $response = $this->postJson('/api/notificacoes', $payloadRuim);

        // 3. VERIFICAR
        // Esperamos um erro 422 (Unprocessable Entity), que é o padrão do Laravel para erro de validação.
        $response->assertStatus(422);

        // Garante que NADA foi salvo no banco. A contagem deve ser zero.
        $this->assertDatabaseCount('notificacoes', 0);
    }

    #[Test]
    public function nao_deve_salvar_duplicatas_exatas()
    {
        // 1. PREPARAR
        $payload = [
            'pacote' => 'com.nubank',
            'texto'  => 'Compra idêntica',
            'data'   => '2026-01-20 15:00:00'
        ];

        // 2. AGIR
        // Enviamos a mesma notificação DUAS vezes seguidas.
        $this->postJson('/api/notificacoes', $payload);
        $this->postJson('/api/notificacoes', $payload);

        // 3. VERIFICAR
        // O banco deve ter apenas 1 registro, não 2. O sistema deve ser inteligente.
        $this->assertDatabaseCount('notificacoes', 1);
    }
}