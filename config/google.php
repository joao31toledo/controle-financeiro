<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Service Account (O Robô)
    |--------------------------------------------------------------------------
    */
    'service' => [
        // Habilita o uso de conta de serviço
        'enable' => true,

        // Aponta para o arquivo JSON que você colocou no storage
        // O base_path garante que ele ache o caminho completo no sistema
        'file' => base_path(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION', 'credentials.json')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Outras configurações (Padrão)
    |--------------------------------------------------------------------------
    */
    'application_name' => env('GOOGLE_APPLICATION_NAME', 'Laravel'),
    'client_id'        => env('GOOGLE_CLIENT_ID', ''),
    'client_secret'    => env('GOOGLE_CLIENT_SECRET', ''),
    'redirect_uri'     => env('GOOGLE_REDIRECT_URI', ''),
    'scopes'           => [
        // O escopo de permissão para ler/escrever planilhas
        \Google\Service\Sheets::SPREADSHEETS,
    ],
    'access_type'      => 'offline',
    'approval_prompt'  => 'auto',
    'prompt'           => 'consent',
];