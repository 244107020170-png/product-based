document.addEventListener("DOMContentLoaded", () => {
    const toggles = document.querySelectorAll(".password-toggle");

    toggles.forEach((toggle) => {
        toggle.addEventListener("click", () => {
            const target = document.getElementById(toggle.dataset.target);

            if (!target) {
                return;
            }

            const isVisible = target.type === "text";

            target.type = isVisible ? "password" : "text";
            toggle.classList.toggle("is-active", !isVisible);
            toggle.setAttribute("aria-pressed", String(!isVisible));
        });
    });
});
