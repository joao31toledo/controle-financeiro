<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class NotificacaoControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function deve_receber_webhook_salvar_notificacao_e_criar_despesa()
    {
        // 1. PREPARAR
        $payload = [
            'pacote' => 'com.nubank',
            'texto'  => 'Compra de R$ 80,00 no iFood',
            'title'  => 'Nubank',
            'time'   => '2026-01-26 20:00:00'
        ];

        // 2. AGIR (Simula o POST na API)
        $response = $this->postJson('/api/notificacoes', $payload);

        // 3. VERIFICAR
        // A) A resposta deve ser 200 (OK) ou 201 (Created)
        $response->assertOk(); 
        
        // B) Deve ter salvo a notificação crua
        $this->assertDatabaseHas('notificacoes', [
            'pacote' => 'com.nubank',
            'texto'  => 'Compra de R$ 80,00 no iFood',
            'status' => 'processado', // O Service já deve ter rodado
        ]);

        $this->assertDatabaseHas('despesas', [
            'loja'   => 'iFood',
            'valor'  => 80.00,
            'status' => 'pendente',
        ]);
    }

    #[Test]
    public function deve_rejeitar_notificacao_invalida()
    {
        // Tenta enviar sem o texto (campo obrigatório)
        $payload = ['pacote' => 'com.nubank'];

        $response = $this->postJson('/api/notificacoes', $payload);

        // Deve retornar erro de validação (422)
        $response->assertUnprocessable();
        
        // Não deve salvar nada
        $this->assertDatabaseCount('notificacoes', 0);
    }
}   