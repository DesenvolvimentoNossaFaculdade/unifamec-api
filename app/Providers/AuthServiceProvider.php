<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        // 2. ADICIONE ESTA LINHA
        Log::info('[DEBUG] AuthServiceProvider está sendo LIDO');

        Gate::before(function ($user, $ability) {

            // 3. ADICIONE ESTA LINHA
            Log::info('[DEBUG] Gate::before foi ACESSADO pelo User: ' . $user->id);

            if ($user->hasRole('Admin Geral', 'api')) {

                // 4. ADICIONE ESTA LINHA
                Log::info('[DEBUG] Admin Geral DETECTADO. Acesso CONCEDIDO.');
                return true;
            }

            Log::info('[DEBUG] Admin Geral NÃO detectado. Continuando...');
            return null;
        });
    }
}