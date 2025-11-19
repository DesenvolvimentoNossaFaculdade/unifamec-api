<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    // ... (index, featured, show, store - MANTENHA IGUAIS) ...
    public function index()
    {
        $courses = Course::orderBy('title', 'asc')->get();
        return CourseResource::collection($courses);
    }

    public function featured()
    {
        $courses = Course::where('is_featured', true)->orderBy('title', 'asc')->get();
        return CourseResource::collection($courses);
    }

    public function show(string $idOrSlug)
    {
        $course = Course::where('id', $idOrSlug)->orWhere('slug', $idOrSlug)->firstOrFail();
        return new CourseResource($course);
    }

    public function store(Request $request)
    {
        // ... (MANTENHA O CÓDIGO DO STORE IGUAL AO ANTERIOR) ...
        // Vou repetir aqui resumido pra não perder contexto, mas pode manter o seu se já estava funcionando
        $this->authorize('cursos:gerenciar');

        if ($request->has('specific_objectives')) $request->merge(['specific_objectives' => json_decode($request->specific_objectives, true)]);
        if ($request->has('curriculum')) $request->merge(['curriculum' => json_decode($request->curriculum, true)]);
        if ($request->has('faculty')) $request->merge(['faculty' => json_decode($request->faculty, true)]);

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
            'graduate_profile' => 'nullable|string',
            'general_objective' => 'nullable|string',
            'instrument_loan_info' => 'nullable|string',
            'specific_objectives' => 'nullable|array',
            'curriculum' => 'nullable|array',
            'faculty' => 'nullable|array',
        ]);

        $thumbnailUrl = null;
        $headerImageUrl = null;

        if ($request->hasFile('thumbnail_file')) {
            $path = $request->file('thumbnail_file')->store('courses', 'uploads');
            $thumbnailUrl = Storage::disk('uploads')->url($path);
        }
        if ($request->hasFile('header_image_file')) {
            $path = $request->file('header_image_file')->store('courses', 'uploads');
            $headerImageUrl = Storage::disk('uploads')->url($path);
        }

        $courseData = array_merge($validated, [
            'thumbnail_url' => $thumbnailUrl,
            'header_image_url' => $headerImageUrl,
        ]);

        $course = Course::create($courseData);

        return (new CourseResource($course))->response()->setStatusCode(201);
    }

    /**
     * Atualiza um curso (CORRIGIDO: Sem trava de contagem)
     */
    public function update(Request $request, Course $course)
    {
        // 1. Decode JSONs
        if ($request->has('specific_objectives')) $request->merge(['specific_objectives' => json_decode($request->specific_objectives, true)]);
        if ($request->has('curriculum')) $request->merge(['curriculum' => json_decode($request->curriculum, true)]);
        if ($request->has('faculty')) $request->merge(['faculty' => json_decode($request->faculty, true)]);

        // 2. Validação (Permite tudo entrar, a gente filtra depois)
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'content' => 'sometimes|string',
            'duration_semesters' => 'sometimes|string',
            'modality' => 'sometimes|string',
            'price' => 'nullable|numeric|min:0',
            'is_featured' => 'sometimes|boolean',
            
            // Imagens
            'thumbnail_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'header_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'clear_thumbnail' => 'nullable|boolean',
            'clear_header_image' => 'nullable|boolean',

            // Detalhes
            'graduate_profile' => 'nullable|string',
            'general_objective' => 'nullable|string',
            'instrument_loan_info' => 'nullable|string',
            'specific_objectives' => 'nullable|array',
            'curriculum' => 'nullable|array',
            'faculty' => 'nullable|array',
        ]);

        $dataToUpdate = [];

        // 3. LÓGICA DE PERMISSÃO (Aditiva)
        
        // Pedagógico / Admin / Marketing (Se tiver 'gerenciar')
        if (auth()->user()->can('cursos:gerenciar')) {
            $dataToUpdate = array_merge($dataToUpdate, $request->only([
                'title', 'description', 'content', 'duration_semesters', 'modality', 'is_featured', 
                'graduate_profile', 'general_objective', 'instrument_loan_info', 
                'specific_objectives', 'curriculum', 'faculty', 'price' // Adicionei preço aqui pro Marketing/Admin
            ]));
        }

        // Secretaria (Só Preço)
        if (auth()->user()->can('cursos:editar-preco')) {
            if ($request->has('price')) {
                $dataToUpdate['price'] = $request->price;
            }
        }

        // Marketing (Imagens) - Se tiver permissão específica ou geral
        if (auth()->user()->can('cursos:editar-imagem') || auth()->user()->can('cursos:gerenciar')) {
            
            // Thumbnail
            if ($request->hasFile('thumbnail_file')) {
                if ($course->thumbnail_url) { 
                    $oldPath = str_replace(Storage::disk('uploads')->url(''), '', $course->thumbnail_url);
                    Storage::disk('uploads')->delete($oldPath); 
                }
                $path = $request->file('thumbnail_file')->store('courses', 'uploads');
                $dataToUpdate['thumbnail_url'] = Storage::disk('uploads')->url($path);
            } elseif ($request->input('clear_thumbnail') == true) {
                if ($course->thumbnail_url) { 
                    $oldPath = str_replace(Storage::disk('uploads')->url(''), '', $course->thumbnail_url);
                    Storage::disk('uploads')->delete($oldPath); 
                }
                $dataToUpdate['thumbnail_url'] = null;
            }

            // Header Image
            if ($request->hasFile('header_image_file')) {
                if ($course->header_image_url) { 
                    $oldPath = str_replace(Storage::disk('uploads')->url(''), '', $course->header_image_url);
                    Storage::disk('uploads')->delete($oldPath); 
                }
                $path = $request->file('header_image_file')->store('courses', 'uploads');
                $dataToUpdate['header_image_url'] = Storage::disk('uploads')->url($path);
            } elseif ($request->input('clear_header_image') == true) {
                if ($course->header_image_url) { 
                    $oldPath = str_replace(Storage::disk('uploads')->url(''), '', $course->header_image_url);
                    Storage::disk('uploads')->delete($oldPath); 
                }
                $dataToUpdate['header_image_url'] = null;
            }
        }

        // 4. SEGURANÇA: Se o array estiver vazio, o usuário não tinha permissão pra nada que enviou
        if (empty($dataToUpdate)) {
            abort(403, 'Você não tem permissão para alterar os campos enviados.');
        }
        
        // 5. ATUALIZAÇÃO (Sem checagem de contagem, apenas salva o permitido)
        $course->update($dataToUpdate);

        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {
        $this->authorize('cursos:gerenciar');

        if ($course->thumbnail_url) { 
            $oldPath = str_replace(Storage::disk('uploads')->url(''), '', $course->thumbnail_url);
            Storage::disk('uploads')->delete($oldPath); 
        }
        
        $course->delete();
        return response()->json(null, 204);
    }
}