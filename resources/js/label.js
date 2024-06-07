<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('label').forEach(label => {
        const input = document.getElementById(label.htmlFor);
        if (input) {
            const style = getComputedStyle(input);
            const marginTop = parseFloat(style.paddingTop) + parseFloat(style.borderTopWidth);
            label.style.top = `${input.offsetTop - marginTop}px`; // Ajuste cette valeur en fonction de votre mise en page
        }
    });
});

</script>
