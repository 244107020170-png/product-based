// TOGGLE PASSWORD
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M2 12C4.6 7.8 8 5.7 12 5.7C16 5.7 19.4 7.8 22 12C19.4 16.2 16 18.3 12 18.3C8 18.3 4.6 16.2 2 12Z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>';
    const eyeOffIcon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M3 3L21 21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M2 12C4.6 7.8 8 5.7 12 5.7C13.7 5.7 15.3 6.1 16.8 6.9M19.6 9.2C20.5 10 21.3 10.9 22 12C19.4 16.2 16 18.3 12 18.3C10.1 18.3 8.4 17.8 6.8 16.8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M14.1 14.1C13.6 14.7 12.8 15 12 15C10.3 15 9 13.7 9 12C9 11.2 9.3 10.4 9.9 9.9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>';

    if (!passwordInput || !togglePassword) return;
    togglePassword.innerHTML = eyeIcon;

    togglePassword.addEventListener('click', () => {
        const isHidden = passwordInput.type === 'password';

        passwordInput.type = isHidden ? 'text' : 'password';
        togglePassword.innerHTML = isHidden ? eyeOffIcon : eyeIcon;
    });
});

// FLOATING RANDOMIZER (BIAR GA KAKU)
const char = document.querySelector('.floating');

if (char) {
    setInterval(() => {
        const randomY = Math.random() * 8; 
        char.style.transform = `translateY(-${randomY}px)`;
    }, 2000);
}


// BUTTON CLICK EFFECT (OPTIONAL)
const buttons = document.querySelectorAll('.btn-main');

buttons.forEach(btn => {
    btn.addEventListener('click', () => {
        btn.style.transform = "scale(0.95)";
        setTimeout(() => {
            btn.style.transform = "scale(1)";
        }, 150);
    });
});
