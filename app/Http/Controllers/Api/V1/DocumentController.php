<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;

// Importe as Habilidades
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class DocumentController extends Controller
{
    // Use as Habilidades
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe uma lista de documentos (PÚBLICO).
     */
    public function index(Request $request)
    {
        $query = Document::where('is_active', true)->orderBy('title');

        // Permite filtrar por categoria: /api/v1/documents?category=manual
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        return DocumentResource::collection($query->get());
    }

    /**
     * Armazena um novo documento (Pedagógico).
     */
    public function store(Request $request)
    {
        $this->authorize('documentos:gerenciar');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'file_url' => 'required|string|max:255', // (No futuro, será um upload de arquivo)
            'category' => 'required|string|max:50',
            'is_active' => 'boolean',
        ]);

        $document = Document::create($validated);

        return (new DocumentResource($document))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Exibe um documento (não usaremos, mas o --api criou).
     */
    public function show(Document $document)
    {
        return new DocumentResource($document);
    }

    /**
     * Atualiza um documento (Pedagógico).
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('documentos:gerenciar');

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'file_url' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:50',
            'is_active' => 'sometimes|boolean',
        ]);

        $document->update($validated);

        return new DocumentResource($document);
    }

    /**
     * Remove um documento (Pedagógico).
     */
    public function destroy(Document $document)
    {
        $this->authorize('documentos:gerenciar');
        $document->delete();
        return response()->json(null, 204);
    }
}