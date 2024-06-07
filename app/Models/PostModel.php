<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Database\Eloquent\Model; // Importation de la classe de base Model

class PostModel extends Model // Déclaration de la classe PostModel qui étend Model
{
    protected $table = 'posts'; // Définition de la table associée à ce modèle
    protected $primaryKey = 'post_id'; // Définition de la clé primaire de la table
    protected $fillable = ['title', 'lead', 'content', 'author_id']; // Définition des attributs pouvant être assignés en masse

    // Définition de la relation entre PostModel et CommentModel
    public function comments()
    {
        // Un post a plusieurs commentaires (relation one-to-many)
        return $this->hasMany(CommentModel::class, 'post_id');
    }

    // Définition de la relation entre PostModel et User pour l'auteur
    public function author()
    {
        // Un post appartient à un utilisateur en tant qu'auteur (relation many-to-one)
        return $this->belongsTo(User::class, 'author_id');
    }

    // Définition de la relation entre PostModel et User (doublon de la méthode author)
    public function user()
    {
        // Un post appartient à un utilisateur (relation many-to-one)
        return $this->belongsTo(User::class, 'author_id');
    }
}
