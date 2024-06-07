<?php

namespace App\Models; // Espace de noms pour le modèle

use Illuminate\Database\Eloquent\Factories\HasFactory; // Importation du trait HasFactory pour les usines de modèles
use Illuminate\Database\Eloquent\Model; // Importation de la classe de base Model

class OrderItemModel extends Model // Déclaration de la classe OrderItemModel qui étend Model
{
    use HasFactory; // Utilisation du trait HasFactory pour permettre l'utilisation d'usines pour ce modèle
    protected $table = 'order_items'; // Définition de la table associée à ce modèle
    protected $primaryKey = 'order_item_id'; // Définition de la clé primaire de la table
    public $timestamps = false; // Désactivation des timestamps (created_at et updated_at)

    protected $fillable = ['order_id', 'item_id', 'quantity', 'price']; // Définition des attributs pouvant être assignés en masse

    // Définition de la relation entre OrderItemModel et ItemModel
    public function item()
    {
        // Un article de commande appartient à un article (relation many-to-one)
        return $this->belongsTo(ItemModel::class, 'item_id');
    }

    // Définition de la relation entre OrderItemModel et OrderModel
    public function order()
    {
        // Un article de commande appartient à une commande (relation many-to-one)
        return $this->belongsTo(OrderModel::class, 'order_id');
    }
}
