<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    // La table users ne gère pas de created_at / updated_at
    public $timestamps = false;

    protected $fillable = [
        'username', 'password', 'email',
        'nom', 'prenom', 'adresse', 'code_postal',
        'date_de_naissance', 'profile_image',
    ];

    protected $hidden = ['password', 'remember_token'];

    public function logins(): HasMany
    {
        return $this->hasMany(LoginModel::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // URL publique de la photo de profil (upload récent ou image historique dans public/assets)
    protected function profileImageUrl(): Attribute
    {
        return Attribute::get(function () {
            if (! $this->profile_image) {
                return null;
            }

            return str_starts_with($this->profile_image, 'profile_images/')
                ? asset('storage/' . $this->profile_image)
                : asset($this->profile_image);
        });
    }
}
