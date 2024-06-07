<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importation du trait HasFactory pour les usines de modèles
use Illuminate\Database\Eloquent\Model; // Importation de la classe de base Model

class MessageModel extends Model // Déclaration de la classe MessageModel qui étend Model
{
    use HasFactory; // Utilisation du trait HasFactory pour permettre l'utilisation d'usines pour ce modèle

    protected $table = 'messages'; // Définition de la table associée à ce modèle
    protected $primaryKey = 'message_id'; // Définition de la clé primaire de la table
    protected $fillable = ['user_id', 'username', 'message']; // Définition des attributs pouvant être assignés en masse

    // Définition de la relation entre MessageModel et User
    public function user()
    {
        // Un message appartient à un utilisateur (relation many-to-one)
        return $this->belongsTo(User::class, 'user_id');
    }
}
