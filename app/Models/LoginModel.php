<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importation du trait HasFactory pour les usines de modèles
use Illuminate\Database\Eloquent\Model; // Importation de la classe de base Model

class LoginModel extends Model // Déclaration de la classe LoginModel qui étend Model
{
    use HasFactory; // Utilisation du trait HasFactory pour permettre l'utilisation d'usines pour ce modèle

    protected $table = 'logins'; // Définition de la table associée à ce modèle
    protected $fillable = ['user_id', 'date']; // Définition des attributs pouvant être assignés en masse
    public $timestamps = false; // Désactivation des timestamps (created_at et updated_at)

    // Définition de la relation entre LoginModel et User
    public function user()
    {
        // Un login appartient à un utilisateur (relation many-to-one)
        return $this->belongsTo(User::class, 'user_id');
    }
}
?>
