<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\AuditLogResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Importe
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit; // Importe o Model de Audit

class AuditLogController extends Controller
{
    use AuthorizesRequests; // Use

    /**
     * Exibe os logs de auditoria.
     */
    public function index()
    {
        // 1. Autorização: Só entra se for Admin Geral
        // (Usamos o 'gate' do Spatie, 'role' funciona melhor aqui)
        if (!auth()->user()->hasRole('Admin Geral')) {
            abort(403, 'Acesso negado.');
        }

        // 2. Busca os logs, mais novos primeiro
        $logs = Audit::with('user') // Carrega o usuário junto
                    ->orderBy('created_at', 'desc')
                    ->paginate(50); // Pagina os resultados

        // 3. Retorna a coleção formatada
        return AuditLogResource::collection($logs);
    }
}