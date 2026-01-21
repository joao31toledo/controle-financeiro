<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use App\Models\Notificacao;
use App\Models\Despesa;
use App\Services\ExpenseService;

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
            'status' => 'pendente'
        ]);

        // 2. AGIR
        $service = new ExpenseService();
        $service->processar($notificacao);

        // 3. VERIFICAR
        // A) A notificação deve ter mudado de status para 'processado'
        $this->assertEquals('processado', $notificacao->fresh()->status);

        // B) Deve existir 1 despesa na tabela 'despesas'
        $this->assertDatabaseCount('despesas', 1);

        // C) Os dados da despesa devem bater com o texto
        $this->assertDatabaseHas('despesas', [
            'descricao' => 'Padaria', // O nome da loja vai para a descrição
            'valor' => 50.00,
            'parcela_atual' => 1,
            'total_parcelas' => 1
        ]);
    }

    #[Test]
    public function deve_explodir_despesas_parceladas()
    {
        // 1. PREPARAR
        // Notificação de compra parcelada
        $notificacao = Notificacao::create([
            'texto' => 'Compra de R$ 100,00 em 5x na Amazon',
            'payload' => [],
            'status' => 'pendente'
        ]);

        // 2. AGIR
        $service = new ExpenseService();
        $service->processar($notificacao);

        // 3. VERIFICAR
        // Deve ter criado 5 linhas no banco de dados (uma para cada mês)
        $this->assertDatabaseCount('despesas', 5);

        // Verifica se a primeira parcela está correta
        $this->assertDatabaseHas('despesas', [
            'valor' => 20.00, // 100 reais dividido por 5
            'parcela_atual' => 1,
            'total_parcelas' => 5
        ]);

        // Verifica se a última parcela está correta
        $this->assertDatabaseHas('despesas', [
            'valor' => 20.00,
            'parcela_atual' => 5,
            'total_parcelas' => 5
        ]);
    }
}