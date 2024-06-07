<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Importation de la classe Authenticatable pour l'authentification
use Illuminate\Notifications\Notifiable; // Importation du trait Notifiable pour les notifications
use Illuminate\Database\Eloquent\Factories\HasFactory; // Importation du trait HasFactory pour les factories

class UserModel extends Authenticatable
{
    use Notifiable, HasFactory; // Utilisation des traits Notifiable et HasFactory

    protected $table = 'users'; // Nom de la table associée à ce modèle
    protected $primaryKey = 'user_id'; // Nom de la clé primaire de la table
    protected $fillable = [ // Attributs pouvant être assignés en masse
        'username', 'password', 'email', 'role', 'nom', 'prenom', 'adresse', 'code_postal', 'date_de_naissance', 'profile_image'
    ];
    protected $hidden = ['password', 'remember_token']; // Attributs à masquer lors de la sérialisation du modèle

    // Eloquent gère les interactions avec la base de données, y compris l'utilisation de requêtes préparées.
    // Cela signifie que toutes les opérations de base de données effectuées par ce modèle (insertions, mises à jour, suppressions, sélections)
    // sont protégées contre les injections SQL sans avoir besoin d'écrire des requêtes préparées explicitement.
}
?>
