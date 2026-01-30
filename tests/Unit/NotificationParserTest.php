<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

use App\Services\ParserService; 

class NotificationParserTest extends TestCase
{
    #[Test]
    public function deve_extrair_valor_e_loja_simples()
    {
        // Cenário: Compra padrão
        $texto = "Compra de R$ 30,00 no Mercado Livre";

        // Ação
        $resultado = ParserService::analisar($texto);

        // Verificação
        $this->assertEquals(30.00, $resultado['valor']); // Valor limpo (float)
        $this->assertEquals('Mercado Livre', $resultado['loja']); // Nome da loja
        $this->assertEquals(1, $resultado['parcelas']); // Padrão é 1x
    }

    #[Test]
    public function deve_lidar_com_formato_nubank()
    {
        $texto = "Compra de R$ 24,20 APROVADA em OXXO ALCEU DE CAMPOS para o cartão com final 1234";

        $resultado = ParserService::analisar($texto);

        $this->assertEquals('criar_despesa', $resultado['acao']);
        $this->assertEquals(24.20, $resultado['valor']);
        
        // Testando a extração do cartão
        $this->assertEquals('1234', $resultado['cartao']);

        // A loja continua sendo o desafio final de limpeza
        $this->assertEquals('OXXO ALCEU DE CAMPOS', $resultado['loja']);
    }
}