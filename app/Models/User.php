<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Foundation\Auth\User as Authenticatable; // Importation de la classe Authenticatable pour l'authentification
use Illuminate\Notifications\Notifiable; // Importation du trait Notifiable pour les notifications
use Illuminate\Database\Eloquent\Relations\HasMany; // Importation de la classe HasMany pour les relations one-to-many

class User extends Authenticatable // Déclaration de la classe User qui étend Authenticatable
{
    use Notifiable; // Utilisation du trait Notifiable pour permettre les notifications

    protected $table = 'users'; // Définition de la table associée à ce modèle
    protected $primaryKey = 'user_id'; // Définition de la clé primaire de la table
    protected $fillable = [ // Définition des attributs pouvant être assignés en masse
        'username', 'password', 'email', 'role',
        'nom', 'prenom', 'adresse', 'code_postal',
        'date_de_naissance', 'profile_image'
    ];

    // Masquer les attributs lors de la sérialisation
    protected $hidden = ['password', 'remember_token'];

    // Désactiver la gestion automatique des horodatages
    public $timestamps = false;

    // Définition de la relation entre User et LoginModel
    public function logins(): HasMany
    {
        // Un utilisateur a plusieurs logins (relation one-to-many)
        return $this->hasMany(LoginModel::class, 'user_id');
    }
}

?>
