<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NavigationMenu extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'slug'];

    public function links()
    {
        return $this->hasMany(NavigationLink::class)->whereNull('parent_id')->orderBy('order');
    }
}