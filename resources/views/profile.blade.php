<!-- resources/views/profile.blade.php -->

<x-layouts.base :title="'Profile'">
  <!-- Utilise le layout de base avec le titre 'Profile' -->

  <p> <br> </p>
  <!-- Un paragraphe avec un saut de ligne pour ajouter de l'espace en haut de la page -->

  @include('components.register-section')
  <!-- Inclusion de la section d'inscription depuis le composant 'register-section' -->
</x-layouts.base>
<!-- Fin du layout de base -->
