<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class CourseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe uma lista de todos os cursos.
     */
    public function index()
    {
        $courses = Course::orderBy('title', 'asc')->get();
        return CourseResource::collection($courses);
    }

    /**
     * Exibe apenas os cursos marcados como "featured".
     */
    public function featured()
    {
        $courses = Course::where('is_featured', true)
                        ->orderBy('title', 'asc')
                        ->get();
                        
        return CourseResource::collection($courses);
    }

    /**
     * Exibe um curso específico pelo ID ou Slug.
     */
    public function show(string $idOrSlug)
    {
        $course = Course::where('id', $idOrSlug)
                        ->orWhere('slug', $idOrSlug)
                        ->firstOrFail();
                        
        return new CourseResource($course);
    }

    /**
     * Armazena um novo curso (Pedagógico).
     */
    public function store(Request $request)
    {
        // 1. Autorização
        $this->authorize('cursos:gerenciar');

        // 2. Validação
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'required|string',
            'duration_semesters' => 'required|string',
            'modality' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'is_featured' => 'boolean',
        ]);

        // 3. Criação
        $course = Course::create($validated);

        // 4. Retorno
        return (new CourseResource($course))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Atualiza um curso (Lógica "UAU" de Permissão).
     */
    public function update(Request $request, Course $course)
    {
        // 1. Autorização Mínima (Tem que ter PELO MENOS uma permissão de curso)
        if (
            !auth()->user()->can('cursos:gerenciar') &&
            !auth()->user()->can('cursos:editar-preco') &&
            !auth()->user()->can('cursos:editar-imagem')
        ) {
            abort(403, 'Você não tem permissão para editar cursos.');
        }

        // 2. Validação (campos são opcionais)
        $validated = $request->validate([
            // (Campos do Pedagógico)
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'content' => 'sometimes|string',
            'duration_semesters' => 'sometimes|string',
            'modality' => 'sometimes|string',
            'is_featured' => 'sometimes|boolean',
            
            // (Campo da Secretaria)
            'price' => 'sometimes|numeric|min:0',

            // (Campos do Marketing) - (thumbnail_url é a 'coverUrl')
            'thumbnail_url' => 'sometimes|string|max:255',
            'header_image_url' => 'sometimes|string|max:255',
        ]);

        // 3. Lógica de Permissão "UAU"
        $dataToUpdate = [];

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
            $dataToUpdate = array_merge($dataToUpdate, $request->only([
                'thumbnail_url', 'header_image_url'
            ]));
        }

        // 4. Checagem de Segurança
        // Se o usuário mandou dados que ele NÃO PODE editar, $dataToUpdate
        // será menor que $validated.
        
        // (Filtra chaves nulas do request)
        $requestedData = array_filter($validated, function($key) use ($request) {
            return $request->has($key);
        }, ARRAY_FILTER_USE_KEY);
        
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
        // 1. Autorização
        $this->authorize('cursos:gerenciar');

        // 2. Deleção
        $course->delete();

        // 3. Retorno
        return response()->json(null, 204);
    }
}