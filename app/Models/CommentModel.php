<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Database\Eloquent\Model; // Importation de la classe de base Model

class CommentModel extends Model // Déclaration de la classe CommentModel qui étend Model
{
    protected $table = 'comments'; // Définition de la table associée à ce modèle
    protected $primaryKey = 'comment_id'; // Définition de la clé primaire de la table
    protected $fillable = ['content', 'post_id', 'author_id', 'visible']; // Définition des attributs pouvant être assignés en masse

    // Assure-toi que les timestamps sont activés
    public $timestamps = true; // Activation des timestamps (created_at et updated_at)

    // Définition de la relation entre CommentModel et PostModel
    public function post()
    {
        // Un commentaire appartient à un post (relation many-to-one)
        // Laravel utilise des requêtes préparées implicitement ici
        return $this->belongsTo(PostModel::class, 'post_id');
    }

    // Définition de la relation entre CommentModel et UserModel
    public function user()
    {
        // Un commentaire appartient à un utilisateur (relation many-to-one)
        // Laravel utilise des requêtes préparées implicitement ici
        return $this->belongsTo(UserModel::class, 'author_id');
    }
}
?>
