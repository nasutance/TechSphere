// Réduit le header et adapte le logo lors du défilement de la page
document.addEventListener('DOMContentLoaded', function () {
    const header = document.querySelector('header');
    const logo = document.querySelector('header .logo');
    const navList = document.querySelector('header nav ul');

    if (!header || !logo) {
        return;
    }

    window.addEventListener('scroll', function () {
        if (window.scrollY > 90) {
            header.style.height = '60px';
            header.style.background = '#f5f7fa';
            logo.style.lineHeight = '60px';
            logo.style.color = 'black';
            if (navList) {
                navList.style.lineHeight = '60px';
            }
        } else {
            // Retour aux valeurs définies dans la feuille de style
            header.style.height = '';
            header.style.background = '';
            logo.style.lineHeight = '';
            logo.style.color = '';
            if (navList) {
                navList.style.lineHeight = '';
            }
        }
    });
});
