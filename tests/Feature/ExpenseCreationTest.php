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

    #[Test]
    public function deve_processar_corretamente_formato_nubank_com_cartao()
    {
        // 1. PREPARAR
        $textoNubank = "Compra de R$ 24,20 APROVADA em OXXO ALCEU DE CAMPOS para o cartão com final 1234";
        
        $notificacao = Notificacao::create([
            'texto' => $textoNubank,
            'payload' => [],
            'status' => 'pendente',
            'pacote' => 'com.nubank', // Campo obrigatório
        ]);

        // 2. AGIR
        $service = new DespesaService();
        $service->processar($notificacao);

        // 3. VERIFICAR
        // A prova real: A despesa foi criada limpando o lixo?
        $this->assertDatabaseHas('despesas', [
            'loja' => 'OXXO ALCEU DE CAMPOS', // Sem o texto do cartão
            'valor' => 24.20,
            'cartao' => '1234', // Capturou o cartão?
            'status' => 'pendente',
        ]);
    }
}