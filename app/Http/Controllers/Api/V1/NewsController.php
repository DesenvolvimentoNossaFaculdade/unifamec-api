<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

// 1. IMPORTE AS HABILIDADES DE AUTORIZAÇÃO E VALIDAÇÃO
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

// 2. ****** ESSA É A LINHA QUE FALTAVA ******
// (Para o Auth::id() funcionar)
use Illuminate\Support\Facades\Auth; 

class NewsController extends Controller
{
    // 3. USE AS HABILIDADES
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe uma lista de notícias.
     */
    public function index()
    {
        $news = News::whereNotNull('published_at')
                    ->orderBy('published_at', 'desc')
                    ->get();
                        
        return NewsResource::collection($news);
    }

    /**
     * Armazena uma nova notícia (Admin).
     */
    public function store(Request $request)
    {
        // Autorização (Agora funciona)
        $this->authorize('noticias:gerenciar'); 
    
        // Validação (Agora funciona)
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'is_featured' => 'boolean',
        ]);

        // Criação (O Auth::id() agora funciona)
        $news = News::create([
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'is_featured' => $validated['is_featured'] ?? false,
            'published_at' => now(),
            'user_id' => Auth::id(), // <-- O culpado do erro
        ]);

        // Retorno (Agora retorna 201 E o JSON)
        return (new NewsResource($news))
                ->response()
                ->setStatusCode(201);
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
    public function update(Request $request, News $news) // O Laravel já busca a notícia pelo ID
    {
        // 1. Autorização
        $this->authorize('noticias:gerenciar');

        // 2. Validação (similar ao store, mas 'required' não é sempre)
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'summary' => 'sometimes|string',
            'content' => 'sometimes|string',
            'is_featured' => 'sometimes|boolean',
        ]);

        // 3. Atualização
        $news->update($validated);
        
        // 4. Auditoria (O pacote 'laravel-auditing' já logou isso!)
        // O Admin Geral vai ver: "Usuário 'João Marketing' atualizou a notícia '...' "

        // 5. Retorno
        return new NewsResource($news);
    }

    /**
     * Remove uma notícia (Admin).
     */
    public function destroy(News $news) // O Laravel já busca a notícia pelo ID
    {
        // 1. Autorização
        $this->authorize('noticias:gerenciar');

        // 2. Deleção
        $news->delete();
        
        // 3. Auditoria (O Admin Geral também vai ver isso!)

        // 4. Retorno (204 significa "Deu certo, mas não tenho nada para te devolver")
        return response()->json(null, 204);
    }
}