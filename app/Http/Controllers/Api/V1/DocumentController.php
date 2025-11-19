<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Request $request)
    {
        $query = Document::where('is_active', true)->orderBy('title');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        return DocumentResource::collection($query->get());
    }

    public function store(Request $request)
    {
        $this->authorize('documentos:gerenciar');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'is_active' => 'boolean',
            'file_upload' => 'required|file|mimes:pdf|max:10240', 
        ]);

        $fileUrl = null;

        if ($request->hasFile('file_upload')) {
            // CORREÇÃO: Usar disco 'uploads'
            $path = $request->file('file_upload')->store('documents', 'uploads');
            $fileUrl = Storage::disk('uploads')->url($path);
        }

        $document = Document::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'is_active' => $validated['is_active'] ?? true,
            'file_url' => $fileUrl,
        ]);

        return (new DocumentResource($document))->response()->setStatusCode(201);
    }

    public function show(Document $document)
    {
        return new DocumentResource($document);
    }

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

        if ($request->hasFile('file_upload')) {
            if ($document->file_url) { 
                // Limpa URL antiga do disco uploads
                $oldPath = str_replace(Storage::disk('uploads')->url(''), '', $document->file_url);
                Storage::disk('uploads')->delete($oldPath); 
            }
            
            // Salva no disco uploads
            $path = $request->file('file_upload')->store('documents', 'uploads');
            $updateData['file_url'] = Storage::disk('uploads')->url($path);
            
        } elseif ($request->input('clear_file') == true) {
             if ($document->file_url) { 
                $oldPath = str_replace(Storage::disk('uploads')->url(''), '', $document->file_url);
                Storage::disk('uploads')->delete($oldPath); 
            }
            $updateData['file_url'] = null;
        }

        $document->update($updateData);

        return new DocumentResource($document);
    }

    public function destroy(Document $document)
    {
        $this->authorize('documentos:gerenciar');

        if ($document->file_url) {
            // Limpa do disco uploads
            $oldPath = str_replace(Storage::disk('uploads')->url(''), '', $document->file_url);
            Storage::disk('uploads')->delete($oldPath);
        }

        $document->delete();
        return response()->json(null, 204);
    }
}