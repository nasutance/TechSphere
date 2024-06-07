$(document).ready(function() { // Attendre que le document soit prêt

  $(window).scroll(function() { // Fonction qui se déclenche lors du défilement de la fenêtre

    var scroll = $(this).scrollTop(), // Récupère la position verticale du défilement
      header = $("header"), // Sélectionne l'élément header
      detay = $(".detay"); // Sélectionne l'élément avec la classe .detay

    if (scroll > 90) { // Si le défilement est supérieur à 90 pixels
      header.css({
        "height": "60px", // Réduit la hauteur du header
        "background": "#f5f7fa" // Change le fond du header
      });

      $(".logo").css({
        "line-height": "60px", // Change la hauteur de la ligne pour centrer verticalement le logo
        "color": "black" // Change la couleur du texte du logo
      });

      detay.css({
        "position": "fixed", // Fixe la position de .detay
        "top": "8px", // Positionne .detay à 8 pixels du haut
        "left": "calc(100vw - (850px)/2)", // Centre .detay horizontalement
        "background": "none", // Supprime le fond de .detay
        "border": "none" // Supprime la bordure de .detay
      });

      $("ul").slideUp(); // Masque les éléments <ul> avec une animation de glissement vers le haut
    } else { // Si le défilement est inférieur ou égal à 90 pixels
      header.css({
        "height": "90px", // Augmente la hauteur du header
        "background": "#021c1e" // Change le fond du header
      });

      $(".logo").css({
        "line-height": "90px", // Change la hauteur de la ligne pour centrer verticalement le logo
        "color": "#6fb98f" // Change la couleur du texte du logo
      });

      detay.css({
        "position": "inherit", // Réinitialise la position de .detay
        "background": "#f5f7fa", // Change le fond de .detay
        "border": "1px solid #aaa" // Ajoute une bordure à .detay
      });

      $("ul").slideDown(); // Affiche les éléments <ul> avec une animation de glissement vers le bas
    }
  });

});
