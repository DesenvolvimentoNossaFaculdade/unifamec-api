<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Garanta que isso está aqui

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // HasApiTokens é do Sanctum

    /**
     * Os atributos que podem ser atribuídos em massa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'title', // Adicionado
        'bio', // Adicionado
        'avatar_url', // Adicionado
        'role', // Adicionado
    ];

    /**
     * Os atributos que devem ser ocultados na serialização (JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Os atributos que devem ser convertidos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Um Autor (User) pode ter várias Notícias (News)
     */
    public function news()
    {
        return $this->hasMany(News::class);
    }
}