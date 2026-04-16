// TOGGLE PASSWORD
const eyeBtn = document.querySelector('.eye');
const passwordInput = document.querySelector('.password-input');

if (eyeBtn && passwordInput) {
    eyeBtn.addEventListener('click', () => {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeBtn.innerText = "🙈";
        } else {
            passwordInput.type = "password";
            eyeBtn.innerText = "👁";
        }
    });
}

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