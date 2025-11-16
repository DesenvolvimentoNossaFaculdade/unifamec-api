<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     *  Exibe uma lista de todos os cursos.
     */
    public function index()
    {
        $courses = Course::orderBy('title', 'asc')->get();
        return CourseResource::collection($courses);
    }

    /**
     *  Armazena um novo curso (será usado pelo Admin).
     */
    public function store(Request $request)
    {
        
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
        
    }

    /**
     *  Remove um curso (será usado pelo Admin).
     */
    public function destroy(Course $course)
    {
        
    }

    /**
     *  Exibe apenas os cursos marcados como "featured".
     */
    public function featured()
    {
        $courses = Course::where('is_featured', true)->orderBy('title', 'asc')->get();

        return CourseResource::collection($courses);
    }
}