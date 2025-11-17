<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage; // 1. IMPORTAR O STORAGE

class DocumentController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe uma lista de documentos (PÚBLICO).
     */
    public function index(Request $request)
    {
        $query = Document::where('is_active', true)->orderBy('title');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        return DocumentResource::collection($query->get());
    }

    /**
     * Armazena um novo documento (Pedagógico) - AGORA COM UPLOAD
     */
    public function store(Request $request)
    {
        $this->authorize('documentos:gerenciar');

        // 2. VALIDAÇÃO ATUALIZADA
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'is_active' => 'boolean',
            
            // Valida o arquivo (PDF, max 10MB)
            'file_upload' => 'required|file|mimes:pdf|max:10240', 
        ]);

        $fileUrl = null;

        // 3. LÓGICA DE UPLOAD
        if ($request->hasFile('file_upload')) {
            // Salva o arquivo em 'storage/app/public/documents'
            $path = $request->file('file_upload')->store('documents', 'public');
            $fileUrl = Storage::url($path);
        }

        // 4. Criação
        $document = Document::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'is_active' => $validated['is_active'] ?? true,
            'file_url' => $fileUrl, // Salva a URL gerada
        ]);

        return (new DocumentResource($document))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Exibe um documento.
     */
    public function show(Document $document)
    {
        // Protegendo o 'show' também, caso não deva ser público
        // $this->authorize('documentos:gerenciar'); 
        return new DocumentResource($document);
    }

    /**
     * Atualiza um documento (Pedagógico) - AGORA COM UPLOAD
     */
    public function update(Request $request, Document $document)
    {
        $this->authorize('documentos:gerenciar');

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'category' => 'sometimes|string|max:50',
            'is_active' => 'sometimes|boolean',
            'file_upload' => 'nullable|file|mimes:pdf|max:10240',
            'clear_file' => 'nullable|boolean',
        ]);
        
        $updateData = $request->only(['title', 'category', 'is_active']);

        // Lógica de Upload
        if ($request->hasFile('file_upload')) {
            // Apaga o arquivo antigo
            if ($document->file_url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $document->file_url)); }
            
            // Salva o novo arquivo
            $path = $request->file('file_upload')->store('documents', 'public');
            $updateData['file_url'] = Storage::url($path);
            
        } elseif ($request->input('clear_file') == true) {
             if ($document->file_url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $document->file_url)); }
            $updateData['file_url'] = null;
        }

        $document->update($updateData);

        return new DocumentResource($document);
    }

    /**
     * Remove um documento (Pedagógico).
     */
    public function destroy(Document $document)
    {
        $this->authorize('documentos:gerenciar');

        // Apaga o arquivo do storage
        if ($document->file_url) {
            Storage::disk('public')->delete(str_replace(Storage::url(''), '', $document->file_url));
        }

        $document->delete();
        return response()->json(null, 204);
    }
}