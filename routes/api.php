<?php

use App\Http\Controllers\Api\V1\CoordinatorController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\StatisticController;
use App\Http\Controllers\Api\V1\HeroSlideController;
use App\Http\Controllers\Api\V1\SiteInfoController;
use App\Http\Controllers\Api\V1\NavigationMenuController;
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

    //? Coordenadore
    Route::get('/coordinators', [CoordinatorController::class, 'index'])->name('coordinators.index');
    Route::get('/coordinators/{user}', [CoordinatorController::class, 'show'])->name('coordinators.show');

    //? Pages
    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');

    Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');

    Route::get('/hero-slides', [HeroSlideController::class, 'index'])->name('hero-slides.index');

    Route::get('/site-info', [SiteInfoController::class, 'index'])->name('site-info.index');

    Route::get('/navigation/{slug}', [NavigationMenuController::class, 'show'])->name('navigation.show');
});