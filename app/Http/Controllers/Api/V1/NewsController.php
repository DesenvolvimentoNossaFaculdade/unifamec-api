<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // 1. IMPORTAR O STORAGE

class NewsController extends Controller
{
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
     * Armazena uma nova notícia (Marketing/Admin).
     */
    public function store(Request $request)
    {
        $this->authorize('noticias:gerenciar'); 

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'content' => 'required|string',
            'is_featured' => 'boolean',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $thumbnailUrl = null;

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('news', 'public');
            $thumbnailUrl = Storage::url($path);
        }

        $news = News::create([
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'content' => $validated['content'],
            'is_featured' => $validated['is_featured'] ?? false,
            'published_at' => now(),
            'user_id' => Auth::id(),
            'thumbnail_url' => $thumbnailUrl, 
        ]);

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
     * Atualiza uma notícia (Marketing/Admin).
     */
    public function update(Request $request, News $news)
    {
        $this->authorize('noticias:gerenciar');

        // 2. VALIDAÇÃO ATUALIZADA
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'summary' => 'sometimes|string',
            'content' => 'sometimes|string',
            'is_featured' => 'sometimes|boolean',
            
            // Aceita uma nova imagem
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            
            // Permite "limpar" a imagem
            'clear_thumbnail' => 'nullable|boolean', 
        ]);
        
        $updateData = $request->only(['title', 'summary', 'content', 'is_featured']);

        // 3. LÓGICA DE UPLOAD NO UPDATE
        if ($request->hasFile('thumbnail_file')) {
            // Apaga a imagem antiga, se existir
            if ($news->thumbnail_url) {
                // Converte a URL ('http://.../storage/...') em caminho ('public/...')
                $oldPath = str_replace(Storage::url(''), '', $news->thumbnail_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            // Salva a nova imagem
            $path = $request->file('thumbnail_file')->store('news', 'public');
            $updateData['thumbnail_url'] = Storage::url($path);
            
        } elseif ($request->input('clear_thumbnail') == true) {
             // Se o usuário marcou "remover imagem"
             if ($news->thumbnail_url) {
                $oldPath = str_replace(Storage::url(''), '', $news->thumbnail_url);
                Storage::disk('public')->delete($oldPath);
            }
            $updateData['thumbnail_url'] = null;
        }

        // 4. Atualização
        $news->update($updateData);

        // 5. Retorno
        return new NewsResource($news);
    }

    /**
     * Remove uma notícia (Marketing/Admin).
     */
    public function destroy(News $news)
    {
        $this->authorize('noticias:gerenciar');

        // Apaga a imagem do storage junto com o post
        if ($news->thumbnail_url) {
            $oldPath = str_replace(Storage::url(''), '', $news->thumbnail_url);
            Storage::disk('public')->delete($oldPath);
        }

        $news->delete();
        
        return response()->json(null, 204);
    }
}