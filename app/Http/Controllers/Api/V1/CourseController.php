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
     *  Exibe uma lista de todos os cursos.
     */
    public function index()
    {
        $courses = Course::orderBy('title', 'asc')->get();
        return CourseResource::collection($courses);
    }

    /**
     *  Exibe apenas os cursos marcados como "featured".
     */
    public function featured()
    {
        $courses = Course::where('is_featured', true)
                        ->orderBy('title', 'asc')
                        ->get();
                        
        return CourseResource::collection($courses);
    }

    /**
     *  Armazena um novo curso (será usado pelo Admin/Pedagogico).
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

        // 3. Criação (AGORA FUNCIONA)
        $course = Course::create($validated);

        // 4. Retorno
        return (new CourseResource($course))
                ->response()
                ->setStatusCode(201);
    }

    /**
     *  Exibe um curso específico pelo ID ou Slug.
     */
    public function show(string $idOrSlug)
    {
        $course = Course::where('id', $idOrSlug)
                        ->orWhere('slug', $idOrSlug)
                        ->firstOrFail();
                        
        return new CourseResource($course);
    }

    /**
     *  Atualiza um curso (será usado pelo Admin).
     */
    public function update(Request $request, Course $course)
    {
        // 1. Autorização Mínima
        if (!auth()->user()->can('cursos:gerenciar') && !auth()->user()->can('cursos:editar-preco')) {
            abort(403, 'Você não tem permissão para editar cursos.');
        }

        // 2. Validação
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'content' => 'sometimes|string',
            'duration_semesters' => 'sometimes|string',
            'modality' => 'sometimes|string',
            'price' => 'sometimes|numeric|min:0',
            'is_featured' => 'sometimes|boolean',
        ]);

        // 3. Lógica de Permissão
        if (auth()->user()->can('cursos:gerenciar')) {
            $course->update($validated);
            
        } elseif (auth()->user()->can('cursos:editar-preco')) {
            if (count($validated) > 1 || !isset($validated['price'])) {
                 abort(403, 'Você só tem permissão para atualizar o preço.');
            }
            $course->update(['price' => $validated['price']]);
        }
        
        // 4. Retorno
        return new CourseResource($course);
    }

    /**
     *  Remove um curso (será usado pelo Admin).
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