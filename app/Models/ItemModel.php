<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importation du trait HasFactory pour les usines de modèles
use Illuminate\Database\Eloquent\Model; // Importation de la classe de base Model

class ItemModel extends Model // Déclaration de la classe ItemModel qui étend Model
{
    use HasFactory; // Utilisation du trait HasFactory pour permettre l'utilisation d'usines pour ce modèle

    protected $table = 'items'; // Définition de la table associée à ce modèle
    protected $primaryKey = 'item_id'; // Définition de la clé primaire de la table
    protected $fillable = ['name', 'price', 'category_id']; // Définition des attributs pouvant être assignés en masse

    public $timestamps = false; // Désactivation des timestamps (created_at et updated_at)

    // Définition de la relation entre ItemModel et CategoryModel
    public function category()
    {
        // Un article appartient à une catégorie (relation many-to-one)
        return $this->belongsTo(CategoryModel::class, 'category_id');
    }
}
