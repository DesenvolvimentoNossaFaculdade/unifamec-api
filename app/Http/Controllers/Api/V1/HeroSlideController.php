<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\HeroSlideResource; // Importe
use App\Models\HeroSlide; // Importe
use Illuminate\Http\Request;

class HeroSlideController extends Controller
{
    /**
     * Exibe os slides ativos do hero.
     */
    public function index()
    {
        $slides = HeroSlide::where('is_active', true)
                           ->orderBy('order', 'asc')
                           ->get();

        return HeroSlideResource::collection($slides);
    }
}