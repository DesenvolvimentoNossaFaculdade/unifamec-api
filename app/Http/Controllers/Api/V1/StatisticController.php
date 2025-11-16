<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\StatisticResource; // Importe
use App\Models\Statistic; // Importe
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    /**
     * Exibe todas as estatísticas.
     */
    public function index()
    {
        $statistics = Statistic::orderBy('order', 'asc')->get();
        return StatisticResource::collection($statistics);
    }
}