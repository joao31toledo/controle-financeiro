<?php

namespace App\Services;

class ParserService
{
    public static function analisar(string $notificacao): array 
    {
        $regex = '/Compra de\s+R\$\s*([\d,.]+)\s+(?:no|na|em)\s+(.+)/i';

        if(preg_match($regex, $notificacao, $matches))
        {
            return[
                'valor' => self::converteValor($matches[1]),
                'loja' => trim($matches[2]),
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
