// ============================
// TaskManager Module (Full Version)
// ============================
const TaskManager = (() => {
    // ============================
    // Private Variables
    // ============================
    let currentEditingTaskId = null;
    let taskModal = null;
    let tasksContainer = null;
    let CSRF_TOKEN = null;
    let ROUTES = null;

    // ============================
    // DOM Cache
    // ============================
    const DOM = {
        taskTemplate: null,
        taskForm: null,
        profileIcon: null,
        profileForm: null,
        editButtons: null,
        saveButtons: null,
        persianDateInput: null,
        startTimeInput: null,
        filterButtons: null,
        customSelect: null,
        modalEl: null
    };

    // ============================
    // Config
    // ============================
    const config = {
        toastDuration: 3000,
        truncateWordsLimit: 4,
        minPasswordLength: 8
    };

    // ============================
    // Private Methods
    // ============================
    const normalizeTask = (task) => ({
        id: task.id,
        title: task.title || '',
        description: task.description || '',
        date: task.date || '',
        startTime: task.start_time || task.startTime || '',
        priority: Number(task.priority || 0),
        completed: Number(task.completed || 0) === 1
    });

    const taskElById = (id) => tasksContainer.querySelector(`.task-card[data-id="${id}"]`);

    const truncateWords = (str, n = config.truncateWordsLimit) => {
        if (!str) return '';
        const words = str.trim().split(/\s+/);
        return words.slice(0, n).join(' ') + (words.length > n ? '...' : '');
    };

    const showToast = (message, type = 'info') => {
        const toast = document.createElement('div');
        toast.className = `position-fixed top-0 end-0 p-3 text-white bg-${type === 'success' ? 'success' : 'danger'} rounded m-3`;
        toast.style.zIndex = '9999';
        toast.innerHTML = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), config.toastDuration);
    };

    const handleApiError = (error, defaultMessage) => {
        console.error(defaultMessage, error);
        showToast(defaultMessage, 'error');
        throw error;
    };

    const updateTodayDate = () => {
        const now = new Date();
        const persianDate = now.toLocaleDateString('fa-IR', { year: 'numeric', month: 'numeric', day: 'numeric' });
        const persianTime = now.toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.querySelectorAll('.task-date').forEach(el => {
            el.textContent = persianDate + ' - ' + persianTime;
        });
    };

    const formatPersianDate = (e) => {
        let val = e.target.value.replace(/\D/g, '');
        if (val.length <= 4) { e.target.value = val; return; }
        let year = val.slice(0, 4), month = '', day = '';
        if (val.length === 5) { month = val.slice(4, 5); }
        else if (val.length === 6) { month = val.slice(4, 5); day = val.slice(5, 6); }
        else if (val.length === 7) { month = val.slice(4, 5); day = val.slice(5, 7); }
        else if (val.length >= 8) { month = val.slice(4, 6); day = val.slice(6, 8); }
        e.target.value = year + (month ? '/' + month : '') + (day ? '/' + day : '');
    };

    const formatTimeInput = (e) => {
        let val = e.target.value.replace(/\D/g, '');
        if (val.length > 2) val = val.slice(0, 2) + ':' + val.slice(2, 4);
        e.target.value = val;
    };

    // ============================
    // Server Requests
    // ============================
    const createServerTask = async (payload) => {
        const formData = new FormData();
        formData.append('_token', CSRF_TOKEN);
        formData.append('title', payload.title);
        formData.append('description', payload.description || '');
        formData.append('date', payload.date || '');
        formData.append('start_time', payload.start_time || '');
        formData.append('priority', payload.priority || 0);
        try {
            const res = await fetch(ROUTES.taskStore, { method: 'POST', body: formData });
            if (!res.ok) throw new Error(await res.text());
            const data = await res.json();
            return normalizeTask(data.task || data);
        } catch (err) { handleApiError(err, 'خطا در ایجاد تسک'); }
    };

    const updateServerTask = async (id, payload) => {
        try {
            const formData = new FormData();
            formData.append('_token', CSRF_TOKEN);
            formData.append('_method', 'PUT');

            if (payload.title !== undefined) formData.append('title', payload.title);
            if (payload.description !== undefined) formData.append('description', payload.description);
            if (payload.date !== undefined) formData.append('date', payload.date);
            if (payload.start_time !== undefined) formData.append('start_time', payload.start_time);
            if (payload.priority !== undefined) formData.append('priority', payload.priority);
            if (payload.completed !== undefined) formData.append('completed', payload.completed ? 1 : 0);

            const res = await fetch(ROUTES.taskUpdate(id), {
                method: 'POST',
                body: formData
            });

            if (!res.ok) {
                const errorText = await res.text();
                throw new Error(errorText || 'خطا در بروزرسانی تسک');
            }

            const data = await res.json();
            return normalizeTask(data.task || data);

        } catch (error) {
            console.error('Update task error:', error);
            showToast('خطا در بروزرسانی تسک', 'error');
            throw error;
        }
    };




    const deleteServerTask = async (id) => {
        const formData = new FormData();
        formData.append('_token', CSRF_TOKEN);
        formData.append('_method', 'DELETE');
        try {
            const res = await fetch(ROUTES.taskDestroy(id), { method: 'POST', body: formData });
            if (!res.ok) throw new Error(await res.text());
            return true;
        } catch (err) { handleApiError(err, 'خطا در حذف تسک'); }
    };

    const updateProfile = async (field, value) => {
        const formData = new FormData();
        formData.append('_token', CSRF_TOKEN);
        formData.append(field, value);
        try {
            const res = await fetch('/profile/update', { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok || !data.success) throw new Error(data.message || 'خطا در بروزرسانی');
            showToast(data.message, 'success');
            return data;
        } catch (err) { showToast(err.message || 'خطا در بروزرسانی اطلاعات', 'error'); throw err; }
    };

    // ============================
    // Task DOM Management
    // ============================
    const fillTaskCard = (el, task) => {
        el.dataset.id = task.id;
        el.dataset.priority = task.priority;
        el.dataset.date = task.date;
        el.dataset.startTime = task.startTime;
        el.dataset.completed = task.completed ? '1' : '0';

        el.querySelector('.task-title').textContent = task.title;

        const textEl = el.querySelector('.task-text');
        textEl.textContent = truncateWords(task.description);
        if (task.description) {
            new bootstrap.Popover(textEl, { html: true, trigger: 'click', placement: 'top', content: task.description });
        }

        const checkbox = el.querySelector('.complete-task input');
        checkbox.checked = task.completed; // ✅ این خط مهمه
        el.classList.toggle('completed', task.completed); // ✅ این خط هم مهمه
    };


    const createTaskElement = (task) => {
        const el = DOM.taskTemplate.querySelector('.task-card').cloneNode(true);
        el.classList.remove('d-none');
        fillTaskCard(el, task);
        tasksContainer.appendChild(el);
    };

    const addTaskDelegation = () => {
        tasksContainer.addEventListener('change', async (e) => {
            if (e.target.matches('.complete-task input[type="checkbox"]')) {
                const taskCard = e.target.closest('.task-card');
                if (!taskCard) return;

                const taskId = taskCard.dataset.id;
                const newState = e.target.checked;
                const originalState = !newState;

                try {
                    // ارسال درخواست به سرور
                    const updated = await updateServerTask(taskId, {
                        completed: newState
                    });

                    // به روزرسانی نهایی بر اساس پاسخ سرور
                    e.target.checked = updated.completed;
                    taskCard.classList.toggle('completed', updated.completed);
                    taskCard.dataset.completed = updated.completed ? '1' : '0';

                } catch (err) {
                    // در صورت خطا، بازگشت به وضعیت قبلی
                    e.target.checked = originalState;
                    taskCard.classList.toggle('completed', originalState);
                    taskCard.dataset.completed = originalState ? '1' : '0';
                    showToast('خطا در بروزرسانی وضعیت تسک', 'error');
                }
                return;
            }
        });

        // بقیه رویدادهای کلیک برای edit و delete و...
        tasksContainer.addEventListener('click', async (e) => {
            const taskCard = e.target.closest('.task-card');
            if (!taskCard) return;
            const taskId = taskCard.dataset.id;

            // Edit
            if (e.target.closest('.edit-task')) {
                const task = {
                    id: taskId,
                    title: taskCard.querySelector('.task-title').textContent,
                    description: taskCard.querySelector('.task-text').textContent,
                    date: taskCard.dataset.date,
                    startTime: taskCard.dataset.startTime || '',
                    priority: taskCard.dataset.priority || 0
                };
                openEditModal(task);
            }

            // Delete
            if (e.target.closest('.delete-task')) {
                if (confirm('آیا از حذف این تسک مطمئن هستید؟')) {
                    try {
                        await deleteServerTask(taskId);
                        taskCard.remove();
                        showToast('تسک با موفقیت حذف شد', 'success');
                    } catch (err) {
                        showToast('خطا در حذف تسک', 'error');
                    }
                }
            }

            // Time Icon Popup
            if (e.target.closest('.ri-time-fill')) {
                const taskTime = taskCard.dataset.date + ' ' + (taskCard.dataset.startTime || '');
                const nowTime = new Date().toLocaleTimeString('fa-IR');
                alert(`زمان شروع: ${taskTime}\nزمان فعلی: ${nowTime}`);
            }
        });
    };

    const applyFilter = (filterType) => {
        const tasks = Array.from(tasksContainer.children);
        switch (filterType) {
            case 'current': tasks.forEach(t => t.style.display = t.classList.contains('completed') ? 'none' : 'block'); break;
            case 'completed': tasks.forEach(t => t.style.display = t.classList.contains('completed') ? 'block' : 'none'); break;
            case 'priority': tasks.forEach(t => t.style.display = 'block'); tasks.sort((a, b) => b.dataset.priority - a.dataset.priority).forEach(t => tasksContainer.appendChild(t)); break;
            case 'date': tasks.forEach(t => t.style.display = 'block'); tasks.sort((a, b) => new Date(b.dataset.date) - new Date(a.dataset.date)).forEach(t => tasksContainer.appendChild(t)); break;
            case 'all': default: tasks.forEach(t => t.style.display = 'block');
        }
    };

    const initCustomSelect = () => {
        if (!DOM.customSelect) return;
        const selectedOption = DOM.customSelect.querySelector('.selected-option');
        const optionsList = DOM.customSelect.querySelector('.options-list');
        const optionItems = DOM.customSelect.querySelectorAll('.option-item');
        let isOpen = false;
        selectedOption.addEventListener('click', e => {
            e.stopPropagation(); isOpen = !isOpen; optionsList.classList.toggle('d-none', !isOpen); selectedOption.classList.toggle('active', isOpen);
        });
        optionItems.forEach(option => {
            option.addEventListener('click', () => {
                selectedOption.textContent = option.textContent;
                selectedOption.dataset.value = option.dataset.value || option.textContent;
                isOpen = false;
                optionsList.classList.add('d-none');
                selectedOption.classList.remove('active');
                applyFilter(selectedOption.dataset.value);
            });
        });
        document.addEventListener('click', () => { if (isOpen) { isOpen = false; optionsList.classList.add('d-none'); selectedOption.classList.remove('active'); } });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && isOpen) { isOpen = false; optionsList.classList.add('d-none'); selectedOption.classList.remove('active'); } });
    };

    const setupProfileHandlers = () => {
        if (DOM.profileIcon && DOM.profileForm) {
            DOM.profileIcon.addEventListener('click', e => {
                e.stopPropagation();
                DOM.profileForm.classList.toggle('d-none');
            });
            document.addEventListener('click', e => {
                if (!DOM.profileForm.contains(e.target) && e.target !== DOM.profileIcon) {
                    DOM.profileForm.classList.add('d-none');
                }
            });
        }
        DOM.editButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const fieldId = btn.dataset.field;
                const saveBtnId = btn.dataset.savebtn;
                const field = document.getElementById(fieldId);
                const saveBtn = document.getElementById(saveBtnId);
                if (field && saveBtn) {
                    field.readOnly = false; field.focus();
                    btn.classList.add('d-none'); saveBtn.classList.remove('d-none');
                    if (fieldId === 'passwordField' && field.value === '******') field.value = '';
                }
            });
        });
        DOM.saveButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const fieldId = btn.dataset.field;
                const field = document.getElementById(fieldId);
                const editBtn = document.querySelector(`.btn-edit[data-field="${fieldId}"]`);
                if (field && editBtn) {
                    const originalValue = field.value;
                    if (fieldId === 'emailField' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field.value)) { alert('ایمیل نامعتبر'); return; }
                    if (fieldId === 'passwordField' && field.value.length < config.minPasswordLength) { alert('رمز حداقل 8 کاراکتر'); return; }
                    field.readOnly = true; btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> در حال ذخیره...';
                    updateProfile(fieldId, field.value).then(() => {
                        btn.classList.add('d-none'); editBtn.classList.remove('d-none'); btn.innerHTML = 'ذخیره'; btn.disabled = false;
                        if (fieldId === 'passwordField') field.value = '******';
                    }).catch(() => { field.value = originalValue; field.readOnly = true; btn.classList.add('d-none'); editBtn.classList.remove('d-none'); btn.innerHTML = 'ذخیره'; btn.disabled = false; });
                }
            });
        });
    };

    const handleTaskFormSubmit = async (e) => {
        e.preventDefault();
        const title = DOM.taskForm.querySelector('#taskTitle').value;
        const date = DOM.persianDateInput.value;
        const startTime = DOM.startTimeInput.value;
        if (!title || !date || !startTime) { alert('فیلدهای اجباری را پر کنید'); return; }
        const payload = { title, description: DOM.taskForm.querySelector('#taskDescription').value || '', date, start_time: startTime, priority: parseInt(DOM.taskForm.querySelector('#taskPriority').value || 0) };
        try {
            let task;
            if (currentEditingTaskId) {
                task = await updateServerTask(currentEditingTaskId, payload);
                const el = taskElById(currentEditingTaskId);
                if (el) fillTaskCard(el, task);
            } else {
                task = await createServerTask(payload);
                createTaskElement(task);
            }
            DOM.taskForm.reset(); currentEditingTaskId = null; taskModal.hide(); showToast('تسک ذخیره شد', 'success');
        } catch (err) { showToast('خطا در ذخیره تسک', 'error'); }
    };

    const openEditModal = (task) => {
        currentEditingTaskId = task.id;
        DOM.taskForm.querySelector('#taskTitle').value = task.title;
        DOM.taskForm.querySelector('#taskDescription').value = task.description || '';
        DOM.persianDateInput.value = task.date || '';
        DOM.startTimeInput.value = task.startTime || '';
        DOM.taskForm.querySelector('#taskPriority').value = task.priority;
        DOM.modalEl.querySelector('#modalTitle').textContent = 'ویرایش تسک';
        taskModal.show();
    };

    const initializeTasks = () => {
        const initialTasks = window.initialTasks || [];
        initialTasks.map(normalizeTask).forEach(task => {
            let el = taskElById(task.id);
            if (!el) {
                createTaskElement(task);
            } else {
                fillTaskCard(el, task); // ✅ اطمینان از sync وضعیت completed
            }
        });
    };


    // ============================
    // Public Methods
    // ============================
    return {
        init() {
            document.addEventListener('DOMContentLoaded', () => {
                CSRF_TOKEN = window.CSRF_TOKEN;
                ROUTES = window.APP_ROUTES;
                DOM.taskTemplate = document.getElementById('task-template');
                tasksContainer = document.querySelector('.tasks-column');
                DOM.taskForm = document.getElementById('taskForm');
                DOM.modalEl = document.getElementById('taskModal');
                taskModal = new bootstrap.Modal(DOM.modalEl);
                DOM.profileIcon = document.querySelector('.profile-img');
                DOM.profileForm = document.getElementById('profileForm');
                DOM.editButtons = document.querySelectorAll('.btn-edit');
                DOM.saveButtons = document.querySelectorAll('.btn-save');
                DOM.persianDateInput = document.getElementById('persianDate');
                DOM.startTimeInput = document.getElementById('startTime');
                DOM.filterButtons = document.querySelectorAll('.filter-btn');
                DOM.customSelect = document.querySelector('.custom-select');

                updateTodayDate();
                setInterval(updateTodayDate, 1000);
                initializeTasks();
                addTaskDelegation();
                initCustomSelect();
                setupProfileHandlers();
                DOM.taskForm.addEventListener('submit', handleTaskFormSubmit);
                DOM.persianDateInput?.addEventListener('input', formatPersianDate);
                DOM.startTimeInput?.addEventListener('input', formatTimeInput);

                DOM.filterButtons.forEach(btn => {
                    btn.addEventListener('click', () => { DOM.filterButtons.forEach(b => b.classList.remove('active')); btn.classList.add('active'); applyFilter(btn.dataset.filter); });
                });

                console.log('✅ TaskManager Initialized Complete');
            });
        },
        openEditModal,
        applyFilter
    };
})();

// Initialize TaskManager
TaskManager.init();
