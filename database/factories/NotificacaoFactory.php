<?php

namespace Database\Factories;

use App\Models\Notificacao;
use App\Services\DespesaService; // <--- Importamos o serviço
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificacaoFactory extends Factory
{
    public function definition(): array
    {
        $valor = fake()->randomFloat(2, 10, 300);
        $loja = fake()->company();
        
        // Montamos um texto que o seu Regex vai aceitar
        // Ex: "Compra de R$ 50,00 no Padaria do Zé"
        $texto = "Compra de R$ " . number_format($valor, 2, ',', '.') . " no " . $loja . "para o cartão com final 1234";

        return [
            'pacote' => 'com.nubank',
            'titulo' => 'Nubank',
            'texto' => $texto,
            'payload' => [], // array vazio json
            'status' => 'pendente',
            'data_notificacao' => fake()->dateTimeBetween('-1 week', 'now'),
        ];
    }

    /**
     * Configura a factory para rodar ações depois de criar o modelo.
     */
    public function configure()
    {
        return $this->afterCreating(function (Notificacao $notificacao) {
            // AQUI ESTÁ A MÁGICA 🪄
            // Assim que a notificação nasce, chamamos o serviço
            $service = new DespesaService();
            $service->processar($notificacao);
        });
    }
}