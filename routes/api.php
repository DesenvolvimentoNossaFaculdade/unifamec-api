<?php

// --- Todos os 'use' statements ---
use App\Http\Controllers\Api\V1\CoordinatorController;
use App\Http\Controllers\Api\V1\CourseController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\StatisticController;
use App\Http\Controllers\Api\V1\HeroSlideController;
use App\Http\Controllers\Api\V1\SiteInfoController;
use App\Http\Controllers\Api\V1\NavigationMenuController;
use App\Http\Controllers\Api\V1\DocumentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AuditLogController;
use App\Http\Controllers\Api\V1\GalleryCategoryController;
use App\Http\Controllers\Api\V1\GalleryImageController;
use App\Http\Controllers\Api\V1\NavigationLinkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ===============================================
// ROTA PÚBLICA DE LOGIN
// ===============================================
Route::post('/v1/login', [AuthController::class, 'login'])->name('login');


// ===============================================
// GRUPO DE ROTAS V1
// ===============================================
Route::prefix('v1')->group(function () {
    
    // --- ROTAS PÚBLICAS (GET) ---
    Route::get('/courses/featured', [CourseController::class, 'featured'])->name('courses.featured');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{idOrSlug}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{idOrSlug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/coordinators', [CoordinatorController::class, 'index'])->name('coordinators.index');
    Route::get('/coordinators/{user}', [CoordinatorController::class, 'show'])->name('coordinators.show');
    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');
    Route::get('/hero-slides', [HeroSlideController::class, 'index'])->name('hero-slides.index');
    Route::get('/site-info', [SiteInfoController::class, 'index'])->name('site-info.index');
    Route::get('/navigation/{slug}', [NavigationMenuController::class, 'show'])->name('navigation.show');
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/gallery/categories', [GalleryCategoryController::class, 'index'])->name('gallery.categories.index');
    Route::get('/gallery/images', [GalleryImageController::class, 'index'])->name('gallery.images.index');


    // --- ROTAS PROTEGIDAS (AUTH) ---
    // (O 'auth:sanctum' sozinho já cuida dos Bearer Tokens)
    
    // ****** A CORREÇÃO "UAU" ESTÁ AQUI ******
    // (Removemos o 'EnsureFrontendRequestsAreStateful' do array)
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/user', function (Request $request) { return $request->user(); })->name('user.me');

        //? News (CRUD do Marketing)
        Route::post('/news', [NewsController::class, 'store'])->name('news.store');
        Route::put('/news/{news}', [NewsController::class, 'update'])->name('news.update');
        Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('news.destroy');

        //? Course (CRUD Pedagógico/Secretaria/Marketing)
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

        //? Hero Slides (CRUD Marketing)
        Route::post('/hero-slides', [HeroSlideController::class, 'store'])->name('hero-slides.store');
        Route::put('/hero-slides/{heroSlide}', [HeroSlideController::class, 'update'])->name('hero-slides.update');
        Route::delete('/hero-slides/{heroSlide}', [HeroSlideController::class, 'destroy'])->name('hero-slides.destroy');

        //? Documentos (CRUD Pedagógico)
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        //? Galeria (CRUD Marketing)
        Route::post('/gallery/categories', [GalleryCategoryController::class, 'store'])->name('gallery.categories.store');
        Route::put('/gallery/categories/{galleryCategory}', [GalleryCategoryController::class, 'update'])->name('gallery.categories.update');
        Route::delete('/gallery/categories/{galleryCategory}', [GalleryCategoryController::class, 'destroy'])->name('gallery.categories.destroy');
        
        Route::post('/gallery/images', [GalleryImageController::class, 'store'])->name('gallery.images.store');
        Route::put('/gallery/images/{galleryImage}', [GalleryImageController::class, 'update'])->name('gallery.images.update');
        Route::delete('/gallery/images/{galleryImage}', [GalleryImageController::class, 'destroy'])->name('gallery.images.destroy');
        
    }); // Fim do grupo 'auth:sanctum'
}); // Fim do grupo 'v1'