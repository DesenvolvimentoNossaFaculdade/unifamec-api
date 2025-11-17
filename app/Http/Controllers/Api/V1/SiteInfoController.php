<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SiteInfoResource;
use App\Models\SiteInfo;
use Illuminate\Http\Request;

// 1. IMPORTAR AS HABILIDADES
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SiteInfoController extends Controller
{
    // 2. USAR AS HABILIDADES
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe as informações globais do site. (PÚBLICO)
     */
    public function index()
    {
        $info = SiteInfo::firstOrFail(); // Pega o primeiro (e único)
        return new SiteInfoResource($info);
    }

    /**
     * Atualiza as informações globais do site (Admin).
     */
    public function update(Request $request)
    {
        // 1. Autorização
        $this->authorize('config:gerenciar');

        // 2. Busca o primeiro (e único) registro
        $info = SiteInfo::firstOrFail();

        // 3. Validação (todos os campos)
        $validated = $request->validate([
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'address_street' => 'nullable|string|max:255',
            'address_city_state' => 'nullable|string|max:255',
            'address_zip' => 'nullable|string|max:20',
            'google_maps_url' => 'nullable|string|max:255',
            'social_facebook' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_linkedin' => 'nullable|string|max:255',
            'social_youtube' => 'nullable|string|max:255',
            'social_tiktok' => 'nullable|string|max:255',
        ]);

        // 4. Atualização
        $info->update($validated);

        // 5. Retorno
        return new SiteInfoResource($info);
    }
}