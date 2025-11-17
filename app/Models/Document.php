<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class Document extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable; 

    protected $fillable = [
        'title',
        'slug',
        'file_url',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Gera o slug automaticamente.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->slug)) {
                $document->slug = Str::slug($document->title);
            }
        });
    }
}