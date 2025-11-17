<?php

use App\Http\Controllers\Api\V1\CoordinatorController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\StatisticController;
use App\Http\Controllers\Api\V1\HeroSlideController;
use App\Http\Controllers\Api\V1\SiteInfoController;
use App\Http\Controllers\Api\V1\NavigationMenuController;
use App\Http\Controllers\Api\V1\DocumentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

// *** LINHA QUE FALTAVA ***
use App\Http\Controllers\Api\V1\AuditLogController;

// ===============================================
// ROTA PÚBLICA DE LOGIN
// ===============================================
// URL: POST /api/v1/login
Route::post('/v1/login', [AuthController::class, 'login'])->name('login');

// ===============================================
// GRUPO DE ROTAS V1
// ===============================================
Route::prefix('v1')->group(function () {
    
    // --- ROTAS PÚBLICAS (GET) ---
    // (Tudo que seu Next.js precisa para exibir o site)
    
    //? Cursos
    Route::get('/courses/featured', [CourseController::class, 'featured'])->name('courses.featured');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{idOrSlug}', [CourseController::class, 'show'])->name('courses.show');

    //? News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{idOrSlug}', [NewsController::class, 'show'])->name('news.show');

    //? Coordenadores
    Route::get('/coordinators', [CoordinatorController::class, 'index'])->name('coordinators.index');
    Route::get('/coordinators/{user}', [CoordinatorController::class, 'show'])->name('coordinators.show');

    //? Pages
    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');

    //? Statistics
    Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');
    
    //? Hero Slides
    Route::get('/hero-slides', [HeroSlideController::class, 'index'])->name('hero-slides.index');

    //? Site Info
    Route::get('/site-info', [SiteInfoController::class, 'index'])->name('site-info.index');

    //? Navigation
    Route::get('/navigation/{slug}', [NavigationMenuController::class, 'show'])->name('navigation.show');

    //? Documentação
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');


    // --- ROTAS PROTEGIDAS (AUTH) ---
    // (Tudo aqui dentro exige um Token de Login)
    Route::middleware('auth:sanctum')->group(function () {
        
        //? Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        // *** CORREÇÃO DO TYPO AQUI (Era RouteL) ***
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

        //? Get Logged User
        Route::get('/user', function (Request $request) {
            return $request->user();
        })->name('user.me');

        //? News (CRUD do Marketing)
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

        //? Course (CRUD Cooder)
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::post('/hero-slides', [HeroSlideController::class, 'store'])->name('hero-slides.store');
        Route::put('/hero-slides/{heroSlide}', [HeroSlideController::class, 'update'])->name('hero-slides.update');
        Route::delete('/hero-slides/{heroSlide}', [HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');

        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        Route::post('/statistics', [StatisticController::class, 'store'])->name('statistics.store');
        Route::put('/statistics/{statistic}', [StatisticController::class, 'update'])->name('statistics.update');
        Route::delete('/statistics/{statistic}', [StatisticController::class, 'destroy'])->name('statistics.destroy');
    });
});