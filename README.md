# TechSphere

Application web communautaire autour de l'actualité tech, développée avec Laravel : un blog avec commentaires modérés, une boutique de matériel informatique avec panier et commandes, un mini-chat entre membres et un tableau de bord d'administration.

Projet réalisé dans le cadre de ma formation en développement web.

## Fonctionnalités

- **Blog / News** : articles, recherche par mots-clés, commentaires et réponses. Chaque commentaire doit être approuvé par un administrateur avant d'être visible.
- **Boutique** : catalogue par catégories, panier stocké en session, passage de commande et historique des achats.
- **Mini-chat** : fil de discussion entre membres connectés, rafraîchi automatiquement toutes les 5 secondes.
- **Espace membre** : inscription, connexion, profil modifiable avec photo.
- **Administration** : création et édition des articles, gestion du catalogue, modération des commentaires, statistiques de connexion et blocage d'utilisateurs.

## Stack technique

- PHP 8.2 / Laravel 11
- MySQL (SQLite en mémoire pour les tests)
- Blade, CSS et JavaScript vanilla
- Pest pour les tests

## Installation

```bash
git clone <url-du-depot>
cd TechSphere

composer install
cp .env.example .env
php artisan key:generate
```

Créer une base MySQL `techsphere`, renseigner les identifiants dans `.env`, puis :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Le site est alors disponible sur http://localhost:8000 avec un jeu de données de démonstration (articles, catalogue, commentaires).

## Comptes de démonstration

| Rôle          | Nom d'utilisateur | Mot de passe |
| ------------- | ----------------- | ------------ |
| Administrateur | `admin`          | `password`   |
| Client        | `demo`            | `password`   |

## Tests

```bash
php artisan test
```

Les tests couvrent les pages publiques, l'authentification (inscription, connexion, comptes bloqués), les droits d'accès à l'administration et le tunnel d'achat (panier, commande, cloisonnement des commandes entre utilisateurs).

## Points d'architecture

- Rôles `client` / `admin` / `blocked` portés par la table `users` ; les routes d'administration sont protégées par un middleware dédié (`EnsureUserIsAdmin`).
- Le panier vit en session ; la commande et ses lignes sont créées dans une transaction au moment du paiement.
- Les commentaires sont invisibles par défaut et passent par une file de modération côté admin.
- Les statistiques de connexion du tableau de bord s'appuient sur un historique des connexions (table `logins`).
