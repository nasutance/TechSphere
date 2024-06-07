<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importation du trait HasFactory pour les usines de modèles
use Illuminate\Database\Eloquent\Model; // Importation de la classe de base Model

class OrderModel extends Model // Déclaration de la classe OrderModel qui étend Model
{
    use HasFactory; // Utilisation du trait HasFactory pour permettre l'utilisation d'usines pour ce modèle

    protected $table = 'orders'; // Définition de la table associée à ce modèle
    protected $primaryKey = 'order_id'; // Définition de la clé primaire de la table
    protected $fillable = ['user_id', 'total']; // Définition des attributs pouvant être assignés en masse

    // Définition de la relation entre OrderModel et OrderItemModel
    public function items()
    {
        // Une commande a plusieurs articles de commande (relation one-to-many)
        return $this->hasMany(OrderItemModel::class, 'order_id');
    }
}
