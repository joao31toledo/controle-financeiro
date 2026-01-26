<?php

namespace App\Services;

class ParserService
{
    public static function analisar(string $notificacao): array 
    {
        $regex = '/Compra de\s+R\$\s*([\d,.]+)(?:\s+APROVADA)?\s+(?:no|na|em)\s+(.+)/i';

        if(preg_match($regex, $notificacao, $matches))
        {
            $valor = self::converteValor($matches[1]);
            $loja = trim($matches[2]);
            $cartao = null;

            // Verifica se tem o padrão de cartão no final do nome da loja
            if (preg_match('/para\s+o\s+cart.+\s+com\s+final\s+(\d+)/i', $loja, $cartaoMatch)) {
                $cartao = $cartaoMatch[1];
                
                // Remove esse texto da loja
                $loja = trim(str_replace($cartaoMatch[0], '', $loja));
            }

            return [
                'acao' => 'criar_despesa',
                'valor' => $valor,
                'loja' => $loja,
                'cartao' => $cartao, // Se não tiver cartão, vai null
                'parcelas' => 1,
            ];
        }

        return [];
    }

    private static function converteValor(string $valor): float
    {
        # remove ponto de milhar:
        $valorLimpo = str_replace('.', '', $valor);

        # troca a vírgula por ponto no decimal
        $valorFormatado = str_replace(',', '.', $valorLimpo);

        return (float) $valorFormatado;
    }
}
