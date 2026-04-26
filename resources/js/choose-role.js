document.addEventListener('DOMContentLoaded', () => {

    const cards = document.querySelectorAll('.role-card');

    cards.forEach(card => {

        // HOVER MASUK
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.03)';
        });

        // HOVER KELUAR
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });

        // CLICK (EFEK TEKAN)
        card.addEventListener('mousedown', () => {
            card.style.transform = 'translateY(5px) scale(0.97)';
        });

        // BALIK LAGI SETELAH KLIK
        card.addEventListener('mouseup', () => {
            card.style.transform = 'translateY(-5px) scale(1.02)';
        });

    });

});