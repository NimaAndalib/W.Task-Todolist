document.addEventListener('DOMContentLoaded', () => {
    const noteText = document.getElementById('noteText');
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const deleteBtn = document.getElementById('deleteBtn');
    const timeStamp = document.getElementById('timeStamp');

    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let currentNoteTime = null;
    let timerInterval = null;
    let placeholderSeconds = 0; // شمارنده موقت قبل از دریافت نوت

    function timeAgoLive(date) {
        const now = new Date();
        const past = new Date(date);
        const diff = Math.floor((now - past) / 1000);
        if (diff < 60) return `${diff} ثانیه پیش`;
        if (diff < 3600) return `${Math.floor(diff / 60)} دقیقه پیش`;
        if (diff < 86400) return `${Math.floor(diff / 3600)} ساعت پیش`;
        return `${Math.floor(diff / 86400)} روز پیش`;
    }

    function startLiveTimer() {
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            if (currentNoteTime) {
                timeStamp.textContent = timeAgoLive(currentNoteTime);
            } else {
                placeholderSeconds++;
                timeStamp.textContent = `${placeholderSeconds} ثانیه پیش`;
            }
        }, 1000);
    }

    async function loadNote() {
        startLiveTimer(); // تایمر موقت از همین الان شروع می‌شه
        try {
            const res = await fetch('/daily-note');
            if (!res.ok) throw new Error('خطا در دریافت نوت');
            const note = await res.json();

            noteText.textContent = note.note || '';
            currentNoteTime = note.updated_at ? new Date(note.updated_at) : new Date();
            placeholderSeconds = 0; // شمارنده موقت ریست شد
            timeStamp.textContent = timeAgoLive(currentNoteTime);
        } catch (err) {
            console.error('Load note error:', err);
        }
    }

    editBtn.addEventListener('click', () => {
        noteText.contentEditable = true;
        noteText.focus();
        saveBtn.style.display = 'inline-block';
    });

    saveBtn.addEventListener('click', async () => {
        const noteContent = noteText.textContent.trim();
        try {
            const res = await fetch('/daily-note', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify({ note: noteContent })
            });
            if (!res.ok) throw new Error('خطا در ذخیره نوت');

            currentNoteTime = new Date(); // زمان لحظه‌ای کلاینت
            placeholderSeconds = 0;
            noteText.contentEditable = false;
            saveBtn.style.display = 'none';
            timeStamp.textContent = timeAgoLive(currentNoteTime);
        } catch (err) {
            console.error('Save note error:', err);
        }
    });

    deleteBtn.addEventListener('click', async () => {
        try {
            const res = await fetch('/daily-note', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF_TOKEN }
            });
            if (!res.ok) throw new Error('خطا در حذف نوت');

            noteText.textContent = '';
            timeStamp.textContent = '';
            noteText.contentEditable = false;
            saveBtn.style.display = 'none';
            currentNoteTime = null;
            placeholderSeconds = 0;
        } catch (err) {
            console.error('Delete note error:', err);
        }
    });

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

    // بروزرسانی خودکار فعالیت‌ها هر 1 ثانیه (اختیاری)
    setInterval(loadActivity, 1000);
});
