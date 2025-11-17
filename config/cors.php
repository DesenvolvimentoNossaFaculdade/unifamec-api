<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API CORS (Cross-Origin Resource Sharing) Configuration
    |--------------------------------------------------------------------------
    */

    // --- 1. A CORREÇÃO "UAU" ESTÁ AQUI ---
    // Adicione 'storage/*' à lista de caminhos permitidos.
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'storage/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];