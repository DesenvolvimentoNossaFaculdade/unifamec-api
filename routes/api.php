<?php

use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//? Route Sanctum
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//? Grupo de Rotas V1
Route::prefix('v1')->group(function () {
    
    //? GET: Cursos(list)
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    //? GET: Curso.
    Route::get('/courses/{idOrSlug}', [CourseController::class, 'show'])->name('courses.show');

    //? News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{idOrSlug}', [NewsController::class, 'show'])->name('news.show');

});