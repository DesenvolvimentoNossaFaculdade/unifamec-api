<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Exibe uma lista de notícias.
     */
    public function index()
    {
        $news = News::whereNotNull('published_at')
                    ->orderBy('published_at', 'desc')
                    ->get();
                        
        // A linha 22 (que deu o erro) é esta, e agora vai funcionar
        return NewsResource::collection($news);
    }

    /**
     * Armazena uma nova notícia (Admin).
     */
    public function store(Request $request)
    {
        // Em breve
    }

    /**
     * Exibe uma notícia específica pelo ID ou Slug.
     */
    public function show(string $idOrSlug)
    {
        $newsItem = News::where('id', $idOrSlug)
                        ->orWhere('slug', $idOrSlug)
                        ->whereNotNull('published_at')
                        ->firstOrFail();
                            
        return new NewsResource($newsItem);
    }

    /**
     * Atualiza uma notícia (Admin).
     */
    public function update(Request $request, News $news)
    {
        // Em breve
    }

    /**
     * Remove uma notícia (Admin).
     */
    public function destroy(News $news)
    {
        // Em breve
    }
}