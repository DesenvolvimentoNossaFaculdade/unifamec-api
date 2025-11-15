<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'duration_semesters',
        'price',
        'modality',
        'thumbnail_url',
        'header_image_url',
        'is_featured',
    ];

    /**
     * Boot a model.
     * Gera o slug automaticamente ao criar ou atualizar.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
            }
        });

        static::updating(function ($course) {
            if (empty($course->slug) || $course->isDirty('title')) {
                $course->slug = Str::slug($course->title);
            }
        });
    }
}