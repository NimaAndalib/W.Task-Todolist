document.addEventListener('DOMContentLoaded', () => {
    // ----- نوت پد -----
    const noteText = document.getElementById('noteText');
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const deleteBtn = document.getElementById('deleteBtn');
    const timeStamp = document.getElementById('timeStamp');

    // ----- فعالیت‌ها -----
    const tasksCompletedText = document.getElementById('tasksCompletedText');
    const tasksCompletedPercent = document.getElementById('tasksCompletedPercent');
    const activityProgressBar = document.getElementById('activityProgressBar');

    // CSRF token
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // بارگذاری نوت
    async function loadNote() {
        const res = await fetch('/note');
        const note = await res.json();
        noteText.textContent = note.text || 'تایپ کنید...';
        timeStamp.textContent = note.updated_at ? new Date(note.updated_at).toLocaleString() : '';
    }

    // ویرایش نوت
    editBtn.addEventListener('click', () => {
        noteText.contentEditable = true;
        noteText.focus();
        saveBtn.style.display = 'inline-block';

        // پاک کردن متن پیش‌فرض اگر همون متن اولیه است
        if (noteText.textContent.trim() === 'تایپ کنید...') {
            noteText.textContent = '';
        }
    });

    // ذخیره نوت
    saveBtn.addEventListener('click', async () => {
        const text = noteText.textContent.trim();
        await fetch('/note', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
            body: JSON.stringify({ text })
        });
        noteText.contentEditable = false;
        saveBtn.style.display = 'none';
        loadNote(); // بروزرسانی متن و زمان
    });

    // حذف نوت
    deleteBtn.addEventListener('click', async () => {
        await fetch('/note', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } });
        noteText.textContent = 'تایپ کنید...';
        timeStamp.textContent = '';
    });

    // بارگذاری اولیه
    loadNote();

    // ----- فعالیت‌ها / پیشرفت -----
    async function loadActivity() {
        try {
            const res = await fetch('/tasks/progress');
            if (!res.ok) throw new Error('Network response not ok');
            const data = await res.json();

            tasksCompletedText.textContent = `${data.completed} تسک تکمیل شده`;
            tasksCompletedPercent.textContent = `${data.percent}%`;
            activityProgressBar.style.width = `${data.percent}%`;
        } catch (err) {
            console.error('Error loading activity:', err);
        }
    }


    loadActivity();

    // بروزرسانی خودکار فعالیت‌ها هر ۵ ثانیه (اختیاری)
    setInterval(loadActivity, 5000);
});
