<?php

namespace App\Services;

class ParserService
{
    public static function analisar(string $texto, ?string $titulo = ''): array 
    {
        // 1. É Nupay? (Verifica se "Nupay" existe no título)
        if ($titulo && stripos($titulo, 'Nupay') !== false) {
            return self::parseNupay($titulo, $texto);
        }

        // 2. Se não for Nupay, tenta o padrão
        return self::parsePadrao($texto);
    }

    private static function parseNupay(string $titulo, string $texto): array
    {
        // Extrai valor do Título (ex: "Compra com Nupay de R$ 50,00")
        if (!preg_match('/R\$\s*([\d,.]+)/i', $titulo, $valorMatch)) {
            return []; // Se não achou valor no título, aborta
        }

        // Extrai loja do Texto (ex: "...APROVADA em NOME DA LOJA")
        if (!preg_match('/APROVADA em\s+(.+)/i', $texto, $lojaMatch)) {
            return []; // Se não achou loja no texto, aborta
        }

        return [
            'acao' => 'criar_despesa',
            'valor' => self::converteValor($valorMatch[1]),
            'loja' => trim($lojaMatch[1]),
            'cartao' => 'Nupay', // Hardcoded pois é Nupay
            'parcelas' => 1,
        ];
    }

    private static function parsePadrao(string $texto): array
    {
        $regex = '/Compra de\s+R\$\s*([\d,.]+)(?:\s+APROVADA)?\s+(?:no|na|em)\s+(.+)/i';

        if (preg_match($regex, $texto, $matches)) {
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
                'cartao' => $cartao,
                'parcelas' => 1,
            ];
        }

        return [];
    }

    /**
     * Helper para limpar moeda BR (1.000,00 -> 1000.00)
     */
    private static function converteValor(string $valor): float
    {
        $valorLimpo = str_replace('.', '', $valor);
        $valorFormatado = str_replace(',', '.', $valorLimpo);

        return (float) $valorFormatado;
    }
}