import './bootstrap';

document.querySelectorAll('.cursor-pointer').forEach(el => {
    el.addEventListener('mouseenter', () => {
        el.classList.add('opacity-80');
    });
    el.addEventListener('mouseleave', () => {
        el.classList.remove('opacity-80');
    });
});

document.querySelectorAll('.soft-shadow').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-4px)';
    });
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
    });
});
