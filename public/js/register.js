document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const toggleBtn = document.getElementById("togglePassword");
    const passwordError = document.getElementById("passwordError");
    const form = document.getElementById("registerForm");

    // نمایش/مخفی کردن رمز عبور
    toggleBtn.addEventListener("click", function() {
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;
        this.classList.toggle("bi-eye-fill");
        this.classList.toggle("bi-eye-slash-fill");
    });

    // اعتبارسنجی تطابق رمزها
    function validatePassword() {
        if (passwordInput.value !== confirmPassword.value) {
            confirmPassword.classList.add('is-invalid');
            passwordError.style.display = 'block';
            return false;
        } else {
            confirmPassword.classList.remove('is-invalid');
            passwordError.style.display = 'none';
            return true;
        }
    }

    // رویدادهای اعتبارسنجی
    confirmPassword.addEventListener('input', validatePassword);

    // ارسال فرم
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validatePassword()) {
            // تغییر وضعیت دکمه
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> در حال ثبت...';
            submitBtn.disabled = true;
            
            // ارسال فرم (شبیه‌سازی)
            setTimeout(() => {
                this.submit();
            }, 1500);
        }
    });
});