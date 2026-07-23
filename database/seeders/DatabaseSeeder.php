<?php

namespace Database\Seeders;

use App\Models\CategoryModel;
use App\Models\CommentModel;
use App\Models\PostModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Remplit la base avec un jeu de données de démonstration.
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'username' => 'admin',
            'email' => 'admin@techsphere.test',
            'nom' => 'Sphere',
            'prenom' => 'Tech',
        ]);

        $demo = User::factory()->create([
            'username' => 'demo',
            'email' => 'demo@techsphere.test',
        ]);

        // Catalogue de la boutique
        $catalogue = [
            'Composants' => [
                ['Processeur 8 cœurs', 329.99],
                ['Carte graphique 12 Go', 549.00],
                ['SSD NVMe 1 To', 89.90],
                ['Mémoire vive 32 Go', 109.50],
            ],
            'Périphériques' => [
                ['Clavier mécanique', 79.99],
                ['Souris sans fil', 39.90],
                ['Écran 27" QHD', 249.00],
            ],
            'Réseau' => [
                ['Routeur Wi-Fi 6', 129.00],
                ['Switch 8 ports', 34.90],
            ],
        ];

        foreach ($catalogue as $categoryName => $items) {
            $category = CategoryModel::create(['name' => $categoryName]);

            foreach ($items as [$name, $price]) {
                $category->items()->create([
                    'name' => $name,
                    'price' => $price,
                ]);
            }
        }

        // Articles du blog
        $posts = [
            [
                'title' => 'Bien choisir sa carte graphique en 2024',
                'lead' => 'Entre les besoins en jeu, en création et en IA, le choix d\'une carte graphique n\'a jamais été aussi vaste. Petit tour d\'horizon pour s\'y retrouver.',
                'content' => 'Avant de choisir une carte graphique, il faut d\'abord définir son usage principal : jeu en 1080p, création de contenu ou calcul intensif. La quantité de mémoire vidéo, la consommation électrique et le refroidissement sont les trois critères à examiner en priorité. Pensez aussi à vérifier la compatibilité avec votre alimentation et votre boîtier avant tout achat.',
            ],
            [
                'title' => 'SSD NVMe : faut-il encore hésiter ?',
                'lead' => 'Les prix des SSD NVMe ont fortement baissé. Est-ce enfin le moment de dire adieu aux disques durs mécaniques ?',
                'content' => 'Avec des débits dix fois supérieurs à ceux d\'un disque SATA et des prix désormais accessibles, le SSD NVMe s\'impose comme le choix par défaut pour un disque système. Les disques mécaniques gardent toutefois leur intérêt pour le stockage de masse, où le prix au téraoctet reste imbattable.',
            ],
            [
                'title' => 'Wi-Fi 6 : ce qui change vraiment',
                'lead' => 'Débits, latence, gestion des appareils multiples : le point sur les apports concrets de la norme Wi-Fi 6 à la maison.',
                'content' => 'Le Wi-Fi 6 apporte surtout une meilleure gestion des réseaux chargés grâce à l\'OFDMA, qui permet de servir plusieurs appareils simultanément. Pour en profiter, il faut néanmoins que vos appareils soient eux aussi compatibles. Dans un logement avec de nombreux objets connectés, le gain de stabilité est réel.',
            ],
        ];

        $createdPosts = [];
        foreach ($posts as $post) {
            $createdPosts[] = PostModel::create([...$post, 'author_id' => $admin->user_id]);
        }

        // Quelques commentaires, dont un en attente de modération
        CommentModel::create([
            'content' => 'Très utile, merci ! Je cherchais justement une carte pour du montage vidéo.',
            'post_id' => $createdPosts[0]->post_id,
            'author_id' => $demo->user_id,
            'visible' => true,
        ]);

        CommentModel::create([
            'content' => 'Et pour un petit budget, vous conseillez quoi ?',
            'post_id' => $createdPosts[0]->post_id,
            'author_id' => $demo->user_id,
            'visible' => false,
        ]);

        CommentModel::create([
            'content' => 'Passé au NVMe le mois dernier, aucun regret.',
            'post_id' => $createdPosts[1]->post_id,
            'author_id' => $demo->user_id,
            'visible' => true,
        ]);
    }
}
