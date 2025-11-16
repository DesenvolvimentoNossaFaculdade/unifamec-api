<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SiteInfoResource; // Importe
use App\Models\SiteInfo; // Importe
use Illuminate\Http\Request;

class SiteInfoController extends Controller
{
    /**
     * Exibe as informações globais do site.
     */
    public function index()
    {
        // Busca o primeiro registro, ou falha (garantindo que o seeder rodou)
        $info = SiteInfo::firstOrFail();

        return new SiteInfoResource($info);
    }
}