<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage; // 1. IMPORTAR O STORAGE

class CourseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    // ... (index, featured, show - permanecem iguais) ...

    public function index()
    {
        $courses = Course::orderBy('title', 'asc')->get();
        return CourseResource::collection($courses);
    }

    public function featured()
    {
        $courses = Course::where('is_featured', true)
                        ->orderBy('title', 'asc')
                        ->get();
                        
        return CourseResource::collection($courses);
    }

    public function show(string $idOrSlug)
    {
        $course = Course::where('id', $idOrSlug)
                        ->orWhere('slug', $idOrSlug)
                        ->firstOrFail();
                        
        return new CourseResource($course);
    }

    /**
     * Armazena um novo curso (Pedagógico) - AGORA COM UPLOAD
     */
    public function store(Request $request)
    {
        $this->authorize('cursos:gerenciar');

        // 2. VALIDAÇÃO ATUALIZADA
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'duration_semesters' => 'required|string',
            'modality' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'header_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $thumbnailUrl = null;
        $headerImageUrl = null;

        // 3. LÓGICA DE UPLOAD
        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('courses', 'public');
            $thumbnailUrl = Storage::url($path);
        }
        if ($request->hasFile('header_image_file')) {
            $path = $request->file('header_image_file')->store('courses', 'public');
            $headerImageUrl = Storage::url($path);
        }

        // 4. Criação
        $course = Course::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'content' => $validated['content'],
            'duration_semesters' => $validated['duration_semesters'],
            'modality' => $validated['modality'],
            'price' => $validated['price'] ?? null,
            'is_featured' => $validated['is_featured'] ?? false,
            'thumbnail_url' => $thumbnailUrl,
            'header_image_url' => $headerImageUrl,
        ]);

        return (new CourseResource($course))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Atualiza um curso (Lógica "UAU" com Upload).
     */
    public function update(Request $request, Course $course)
    {
        // 1. Autorização Mínima
        if (
            !auth()->user()->can('cursos:gerenciar') &&
            !auth()->user()->can('cursos:editar-preco') &&
            !auth()->user()->can('cursos:editar-imagem')
        ) {
            abort(403, 'Você não tem permissão para editar cursos.');
        }

        // 2. VALIDAÇÃO ATUALIZADA
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'content' => 'sometimes|string',
            'duration_semesters' => 'sometimes|string',
            'modality' => 'sometimes|string',
            'is_featured' => 'sometimes|boolean',
            'price' => 'sometimes|numeric|min:0',
            
            // Campos de Imagem (Marketing)
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'header_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'clear_thumbnail' => 'nullable|boolean',
            'clear_header_image' => 'nullable|boolean',
        ]);

        $dataToUpdate = [];

        // 3. LÓGICA DE PERMISSÃO "UAU"
        
        // Pedagógico (Conteúdo)
        if (auth()->user()->can('cursos:gerenciar')) {
            $dataToUpdate = array_merge($dataToUpdate, $request->only([
                'title', 'description', 'content', 'duration_semesters', 'modality', 'is_featured'
            ]));
        }

        // Secretaria (Preço)
        if (auth()->user()->can('cursos:editar-preco')) {
            $dataToUpdate = array_merge($dataToUpdate, $request->only(['price']));
        }

        // Marketing (Imagens)
        if (auth()->user()->can('cursos:editar-imagem')) {
            // Lógica de Upload para Thumbnail
            if ($request->hasFile('thumbnail_file')) {
                if ($course->thumbnail_url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $course->thumbnail_url)); }
                $path = $request->file('thumbnail_file')->store('courses', 'public');
                $dataToUpdate['thumbnail_url'] = Storage::url($path);
            } elseif ($request->input('clear_thumbnail') == true) {
                if ($course->thumbnail_url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $course->thumbnail_url)); }
                $dataToUpdate['thumbnail_url'] = null;
            }

            // Lógica de Upload para Header Image
            if ($request->hasFile('header_image_file')) {
                if ($course->header_image_url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $course->header_image_url)); }
                $path = $request->file('header_image_file')->store('courses', 'public');
                $dataToUpdate['header_image_url'] = Storage::url($path);
            } elseif ($request->input('clear_header_image') == true) {
                if ($course->header_image_url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $course->header_image_url)); }
                $dataToUpdate['header_image_url'] = null;
            }
        }

        // 4. Checagem de Segurança
        // (Remove os campos de 'clear' e 'file' da contagem de validação)
        $validatedKeys = array_keys($validated);
        $fileKeys = ['thumbnail_file', 'header_image_file', 'clear_thumbnail', 'clear_header_image'];
        $requestedData = array_diff_key($validated, array_flip($fileKeys));

        if (count($dataToUpdate) < count($requestedData)) {
             abort(403, 'Você está tentando atualizar campos que não tem permissão.');
        }

        // 5. Atualização
        $course->update($dataToUpdate);

        // 6. Retorno
        return new CourseResource($course);
    }

    /**
     * Remove um curso (Pedagógico).
     */
    public function destroy(Course $course)
    {
        $this->authorize('cursos:gerenciar');

        // Apaga as imagens junto
        if ($course->thumbnail_url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $course->thumbnail_url)); }
        if ($course->header_image_url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $course->header_image_url)); }

        $course->delete();

        return response()->json(null, 204);
    }
}