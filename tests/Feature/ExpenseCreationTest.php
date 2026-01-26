<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Models\Notificacao;
use App\Models\Despesa;
use App\Services\DespesaService;

class ExpenseCreationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function deve_criar_uma_despesa_simples_a_partir_da_notificacao()
    {
        // 1. PREPARAR
        $notificacao = Notificacao::create([
            'texto' => 'Compra de R$ 50,00 na Padaria',
            'payload' => [],
            'status' => 'pendente',
            'pacote' => 'com.exemplo.pacote',
        ]);

        // 2. AGIR
        $service = new DespesaService();
        $service->processar($notificacao);

        // 3. VERIFICAR
        // A) A notificação deve ter mudado de status para 'processado'
        $this->assertEquals('processado', $notificacao->fresh()->status);

        // B) Deve existir 1 despesa na tabela 'despesas'
        $this->assertDatabaseCount('despesas', 1);

        // C) Os dados da despesa devem bater com o texto
        $this->assertDatabaseHas('despesas', [
            'loja' => 'Padaria', // O nome da loja vai para a descrição
            'valor' => 50.00,
            'status' => 'pendente',
            'cartao' => null,
        ]);
    }
}