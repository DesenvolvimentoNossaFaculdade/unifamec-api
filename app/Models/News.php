<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'thumbnail_url',
        'header_image_url',
        'is_featured',
        'user_id',
        'published_at',
    ];

    /**
     * Atributos que devem ser convertidos para tipos nativos.
     */
    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    /**
     * Gera o slug automaticamente.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });

        static::updating(function ($news) {
            if (empty($news->slug) || $news->isDirty('title')) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    /**
     * Define o relacionamento: Uma Notícia pertence a um Autor (User).
     */
    public function author()
    {
        
    }
}