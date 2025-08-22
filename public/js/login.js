document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');

    if (passwordInput && togglePassword) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // تغییر آیکون چشم
            this.classList.toggle('bi-eye-fill');
            this.classList.toggle('bi-eye-slash-fill');

            // تغییر عنوان برای دسترسی‌پذیری
            const toggleText = type === 'password' ? 'نمایش رمز عبور' : 'مخفی کردن رمز عبور';
            this.setAttribute('title', toggleText);
        });

        // مقدار اولیه برای title
        togglePassword.setAttribute('title', 'نمایش رمز عبور');
    } else {
        console.error('عناصر ورودی رمز عبور یا دکمه نمایش یافت نشدند!');
    }
});