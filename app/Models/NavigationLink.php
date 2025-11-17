<?php

namespace App\Models; // <-- CORRIGIDO

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NavigationLink extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    
    protected $fillable = ['navigation_menu_id', 'parent_id', 'label', 'url', 'order', 'target_blank'];
    protected $casts = ['target_blank' => 'boolean'];

    public function children()
    {
        return $this->hasMany(NavigationLink::class, 'parent_id')->orderBy('order');
    }
}