document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    toggleBtn.addEventListener("click", function () {
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;
        this.classList.toggle("bi-eye-fill");
        this.classList.toggle("bi-eye-slash-fill");
    });
    if (passwordInput && togglePassword) {
        togglePassword.setAttribute('title', 'نمایش رمز عبور');
    } else {
        console.error('عناصر ورودی رمز عبور یا دکمه نمایش یافت نشدند!');
    }
});
