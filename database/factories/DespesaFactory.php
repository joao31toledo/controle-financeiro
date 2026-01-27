<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DespesaFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Gera um nome de empresa aleatório (ex: "Acme Ltd")
            'loja' => fake()->company(), 
            
            // Gera um valor entre R$ 10 e R$ 500
            'valor' => fake()->randomFloat(2, 10, 500), 
            
            // Gera 4 dígitos aleatórios simulando final de cartão
            'cartao' => fake()->numerify('####'), 
            
            // Escolhe aleatoriamente um status
            'status' => fake()->randomElement(['pendente', 'aprovado', 'rejeitado']),
            
            // Distribui as datas nos últimos 30 dias (bom pra testar a ordenação)
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}