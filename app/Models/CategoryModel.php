<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importation du trait HasFactory pour les usines de modèles
use Illuminate\Database\Eloquent\Model; // Importation de la classe de base Model

class CategoryModel extends Model // Déclaration de la classe CategoryModel qui étend Model
{
    use HasFactory; // Utilisation du trait HasFactory pour permettre l'utilisation d'usines pour ce modèle

    protected $table = 'categories'; // Définition de la table associée à ce modèle
    protected $primaryKey = 'category_id'; // Définition de la clé primaire de la table
    protected $fillable = ['name']; // Définition des attributs pouvant être assignés en masse

    // Définition de la relation entre CategoryModel et ItemModel
    public function items()
    {
        // Une catégorie a plusieurs articles (relation one-to-many)
        // Laravel utilise des requêtes préparées implicitement ici
        return $this->hasMany(ItemModel::class, 'category_id');
    }
}
?>
