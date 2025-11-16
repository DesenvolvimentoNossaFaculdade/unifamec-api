<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavigationLink extends Model
{
    use HasFactory;
    protected $fillable = ['navigation_menu_id', 'parent_id', 'label', 'url', 'order', 'target_blank'];
    protected $casts = ['target_blank' => 'boolean'];

    /**
     * Um link pode ter muitos "filhos" (sub-links).
     * Este é o relacionamento recursivo.
     */
    public function children()
    {
        return $this->hasMany(NavigationLink::class, 'parent_id')->orderBy('order');
    }
}