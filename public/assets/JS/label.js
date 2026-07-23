// Aligne chaque label sur le haut de son champ associé
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('label').forEach(function (label) {
        const input = document.getElementById(label.htmlFor);
        if (input) {
            const style = getComputedStyle(input);
            const offset = parseFloat(style.paddingTop) + parseFloat(style.borderTopWidth);
            label.style.top = (input.offsetTop - offset) + 'px';
        }
    });
});
