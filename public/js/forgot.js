document.addEventListener('DOMContentLoaded', function () {
    // عناصر DOM
    const emailStep = document.querySelector('.email-step');
    const otpStep = document.querySelector('.otp-step');
    const emailInput = document.getElementById('email');
    const codeInput = document.getElementById('code');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendBtn = document.getElementById('resendBtn');
    const timerElement = document.getElementById('timer');
    const emailError = document.querySelector('.email-error');
    const codeError = document.querySelector('.code-error');

    // تنظیمات سیستم
    const OTP_EXPIRY_TIME = 120; // 2 دقیقه
    const TEST_OTP = '12345'; // فقط برای محیط توسعه
    let countdown;
    let timerInterval;

    // ==================== توابع کمکی ====================
    const validateEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

    const formatTime = (seconds) => {
        const mins = Math.floor(seconds / 60).toString().padStart(2, '0');
        const secs = (seconds % 60).toString().padStart(2, '0');
        return `${mins}:${secs}`;
    };

    // ==================== مدیریت ایمیل ====================
    emailInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g, '');
        emailError.style.display = 'none';
    });

    // ==================== ارسال کد تایید ====================
    sendOtpBtn.addEventListener('click', async function () {
        if (!validateEmail(emailInput.value)) {
            emailError.textContent = 'لطفاً یک ایمیل معتبر وارد کنید';
            emailError.style.display = 'block';
            return;
        }

        try {
            // شبیه‌سازی ارسال کد (در پروژه واقعی با fetch جایگزین شود)
            console.log(`کد تایید به ${emailInput.value} ارسال شد: ${TEST_OTP}`);

            emailStep.style.display = 'none';
            otpStep.style.display = 'block';
            startTimer();
        } catch (error) {
            console.error('خطا در ارسال کد:', error);
            emailError.textContent = 'خطا در ارسال کد تایید';
            emailError.style.display = 'block';
        }
    });

    // ==================== مدیریت تایمر ====================
    const startTimer = () => {
        clearInterval(timerInterval);
        countdown = OTP_EXPIRY_TIME;
        resendBtn.disabled = true;
        timerElement.textContent = formatTime(countdown);

        timerInterval = setInterval(() => {
            countdown--;
            timerElement.textContent = formatTime(countdown);

            if (countdown <= 0) {
                clearInterval(timerInterval);
                resendBtn.disabled = false;
            }
        }, 1000);
    };

    // ==================== تایید کد ====================
    codeInput.addEventListener('input', function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 5);
        verifyOtpBtn.disabled = this.value.length !== 5;
        codeError.style.display = 'none';
    });

    verifyOtpBtn.addEventListener('click', function () {
        if (codeInput.value !== TEST_OTP) {
            codeError.textContent = 'کد تایید نادرست است';
            codeError.style.display = 'block';
            return;
        }

        // در پروژه واقعی باید با fetch بررسی شود
        window.location.href = window.loginRoute;
    });

    // ==================== ارسال مجدد کد ====================
    resendBtn.addEventListener('click', function () {
        if (!resendBtn.disabled) {
            console.log(`کد جدید به ${emailInput.value} ارسال شد: ${TEST_OTP}`);
            codeInput.value = '';
            verifyOtpBtn.disabled = true;
            startTimer();
        }
    });
});