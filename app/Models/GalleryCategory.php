<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GalleryCategory extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'slug', 'order'];

    // Uma Categoria tem muitas Imagens
    public function images()
    {
        return $this->hasMany(GalleryImage::class)->orderBy('order');
    }
}