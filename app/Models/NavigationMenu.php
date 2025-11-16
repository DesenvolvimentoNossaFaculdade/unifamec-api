<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationMenu extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug'];

    /**
     * Um menu tem muitos links.
     * Pegamos apenas os links "raiz" (que não têm pai).
     */
    public function links()
    {
        return $this->hasMany(NavigationLink::class)->whereNull('parent_id')->orderBy('order');
    }
}