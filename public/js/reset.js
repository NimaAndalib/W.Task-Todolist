document.addEventListener('DOMContentLoaded', function() {
    // انتخاب المان‌ها
    const passInput = document.getElementById("passInput");
    const pass2Input = document.getElementById("pass2Input");
    const toggleBtn = document.getElementById("togglePassword");
    const errorMsg1 = document.getElementById("errorMessage1");
    const errorMsg2 = document.getElementById("errorMessage2");
    const submitBtn = document.getElementById("submit");
    const form = document.getElementById("resetPasswordForm");

    // نمایش/مخفی کردن رمز عبور
    toggleBtn.addEventListener("click", function() {
        const isHidden = passInput.type === "password";
        passInput.type = isHidden ? "text" : "password";
        this.classList.toggle("bi-eye-fill");
        this.classList.toggle("bi-eye-slash-fill");
    });

    // اعتبارسنجی رمز عبور
    function validatePasswords(validateAll = false) {
        let isValid = true;

        // بررسی طول رمز
        if (validateAll || document.activeElement === passInput) {
            const isValidLength = passInput.value.length >= 8;
            passInput.classList.toggle('is-invalid', !isValidLength && passInput.value.length > 0);
            errorMsg1.classList.toggle('d-none', isValidLength || passInput.value.length === 0);
            isValid = isValid && (isValidLength || passInput.value.length === 0);
        }

        // بررسی تطابق رمزها
        if (validateAll || document.activeElement === pass2Input) {
            const isMatch = passInput.value === pass2Input.value;
            pass2Input.classList.toggle('is-invalid', !isMatch && pass2Input.value.length > 0);
            errorMsg2.classList.toggle('d-none', isMatch || pass2Input.value.length === 0);
            isValid = isValid && (isMatch || pass2Input.value.length === 0);
        }

        submitBtn.disabled = !(passInput.value.length >= 8 && passInput.value === pass2Input.value);
        
        return isValid;
    }

    // رویدادهای اعتبارسنجی
    passInput.addEventListener('input', () => validatePasswords());
    pass2Input.addEventListener('input', () => validatePasswords());
    passInput.addEventListener('blur', () => validatePasswords());
    pass2Input.addEventListener('blur', () => validatePasswords());

    // ارسال فرم
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validatePasswords(true)) {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> در حال انتقال...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                window.location.href = window.loginRoute;
            }, 1500);
        }
    });

    // غیرفعال کردن آیکون خطای پیش‌فرض بوت‌استرپ
    const style = document.createElement('style');
    style.innerHTML = `
        .form-control.is-invalid {
            background-image: none !important;
            padding-right: 12px !important;
        }
    `;
    document.head.appendChild(style);
});