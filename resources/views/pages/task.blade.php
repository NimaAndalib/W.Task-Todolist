@extends('layouts.app')

@section('title', 'taskbar')

@section('content')
    <div class="task-container">
        <div class="dashboard-header row align-items-center py-3">
            <div class="col-md-6 d-flex align-items-center">
                <!-- profile -->
                <div class="profile-wrap position-relative w-100">
                    <div class="profile-icon me-3">
                        <img src="{{ asset('img/prof-icon.png') }}" class="rounded-circle profile-img"
                            style="cursor:pointer; width:50px; height:50px;">
                    </div>
                    <!-- فرم: چسبیده به چپ والد + عرض کامل -->
                    <div id="profileForm" class="card shadow p-3 bg-dark text-white position-absolute mt-2 d-none w-100"
                        style="left:0; top:100%; z-index:1055;">
                        <!-- نام -->
                        <div class="mb-3">
                            <label class="form-label">نام</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-dark text-white" id="nameField"
                                    value="کاربر نمونه" readonly>
                                <button class="btn btn-outline-secondary btn-edit" data-field="nameField"
                                    data-savebtn="saveNameBtn">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button id="saveNameBtn" class="btn btn-success btn-save d-none"
                                    data-field="nameField">ذخیره</button>
                            </div>
                        </div>
                        <!-- ایمیل -->
                        <div class="mb-3">
                            <label class="form-label">ایمیل</label>
                            <div class="input-group">
                                <input type="email" class="form-control bg-dark text-white" id="emailField"
                                    value="example@email.com" readonly>
                                <button class="btn btn-outline-secondary btn-edit" data-field="emailField"
                                    data-savebtn="saveEmailBtn">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button id="saveEmailBtn" class="btn btn-success btn-save d-none"
                                    data-field="emailField">ذخیره</button>
                            </div>
                        </div>
                        <!-- پسورد -->
                        <div class="mb-2">
                            <label class="form-label">پسورد</label>
                            <div class="input-group">
                                <input type="password" class="form-control bg-dark text-white" id="passwordField"
                                    value="******" readonly>
                                <button class="btn btn-outline-secondary btn-edit" data-field="passwordField"
                                    data-savebtn="savePasswordBtn">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button id="savePasswordBtn" class="btn btn-success btn-save d-none"
                                    data-field="passwordField">ذخیره</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb-nav list-inline">
                        <li><a href="{{ route('dashboard') }}">خانه</a></li>
                        <span class="mx-2">/</span>
                        <li><a href="{{ route('task') }}">تسک</a></li>
                        <span class="mx-2">/</span>
                        <li><span class="task-date d-block" style="font-size: 18px; margin: 2px 0 0;"></span></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <div class="dashboard-actions col-12 mt-4">
        <div class="col-md-12">
            <div class="row align-items-center d-flex">
                <div class="col-md-3">
                    <div class="custom-select py-2 btn">
                        <div class="selected-option text-center p-0">تسک جاری</div>
                        <ul class="options-list">
                            <li class="option-item">تسک جاری</li>
                            <li class="option-item">تسک پایان یافته</li>
                        </ul>
                    </div>
                </div>
                <div class="col-5"></div>
                <div class="col-md-3">
                    <button class="btn btn-create-task float-end" data-bs-toggle="modal" data-bs-target="#taskModal">
                        ساخت تسک
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-4 tasks-column">
        <!-- تسک‌ها اینجا اضافه می‌شوند -->
    </div>
    <!-- مودال ساخت تسک -->
    <div class="modal fade modal-dark" id="taskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">ساخت تسک جدید</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm" autocomplete="off">
                        <!-- عنوان -->
                        <div class="mb-3">
                            <label class="form-label">عنوان تسک</label>
                            <input type="text" class="form-control" id="taskTitle" placeholder="عنوان را وارد کنید"
                                required>
                        </div>
                        <!-- تاریخ و ساعت -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">تاریخ</label>
                                <input type="text" id="persianDate" class="form-control" style="direction: rtl;"
                                    placeholder="1404/01/04" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">ساعت شروع</label>
                                <input type="text" id="startTime" class="form-control" placeholder="13:00" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">ساعت پایان</label>
                                <input type="text" id="endTime" class="form-control" placeholder="13:30" required>
                            </div>
                        </div>
                        <!-- توضیحات -->
                        <div class="mb-3">
                            <label class="form-label">توضیحات</label>
                            <textarea class="form-control" rows="3" placeholder="توضیحات را وارد کنید"
                                style="height: 128px;min-height:118px;max-height:118px;" id="taskDescription"></textarea>
                        </div>
                        <!-- میزان اهمیت -->
                        <div class="mb-3">
                            <label class="form-label">میزان اهمیت</label>
                            <input type="range" id="taskPriority" min="0" max="3" class="form-range">
                        </div>
                        <div class="modal-btnbox d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-light me-2" data-bs-dismiss="modal">انصراف</button>
                            <button type="submit" class="btn btn-primary">ذخیره تسک</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- تمپلیت تسک (مخفی) -->
    <div id="task-template" class="task-template d-none">
        <div class="task-card">
            <div class="task-body w-100 d-flex justify-content-between align-items-center">
                <div class="task-right__box d-flex">
                    <div class="form-check complete-task">
                        <input type="checkbox" class="form-check-input">
                    </div>
                    <h6 class="task-title"></h6>
                </div>
                <div class="task-center__box" style="height: 79%;">
                    <p class="task-text"></p>
                </div>
                <div class="task-end__box gap-2 d-flex align-items-center" style="font-size: 19px;">
                    <div class="task-icon">
                        <i class="ri-time-fill"></i>
                    </div>
                    <div class="task-icon edit-task">
                        <i class="ri-edit-2-fill"></i>
                    </div>
                    <div class="task-icon delete-task">
                        <i class="ri-delete-bin-5-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // ======== نمایش تاریخ امروز ========
                const updateTodayDate = () => {
                    const today = new Date();
                    const persianDate = today.toLocaleDateString('fa-IR', { year: 'numeric', month: 'numeric', day: 'numeric' });
                    document.querySelectorAll('.task-date').forEach(el => el.textContent = persianDate);
                };
                updateTodayDate();

                // ======== Flatpickr ========
                if (typeof flatpickr !== 'undefined') {
                    flatpickr("#persianDate", { locale: "fa", dateFormat: "Y/m/d" });
                    flatpickr("#startTime, #endTime", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true });
                }

                // ======== Auto-formatting تاریخ و ساعت ========
                const persianDateInput = document.getElementById('persianDate');
                const startTimeInput = document.getElementById('startTime');
                const endTimeInput = document.getElementById('endTime');

                const formatDateInput = (e) => {
                    let val = e.target.value.replace(/\D/g, '');
                    if (val.length >= 5) val = val.slice(0, 4) + '/' + val.slice(4, 6) + '/' + val.slice(6, 8);
                    else if (val.length >= 3) val = val.slice(0, 4) + '/' + val.slice(4, 6);
                    e.target.value = val;
                };

                const formatTimeInput = (e) => {
                    let val = e.target.value.replace(/\D/g, '');
                    if (val.length > 2) val = val.slice(0, 2) + ':' + val.slice(2, 4);
                    e.target.value = val;
                };

                if (persianDateInput) persianDateInput.addEventListener('input', formatDateInput);
                if (startTimeInput) startTimeInput.addEventListener('input', formatTimeInput);
                if (endTimeInput) endTimeInput.addEventListener('input', formatTimeInput);

                // ======== ویرایش پروفایل ========
                const profileWrap = document.querySelector('.profile-wrap');
                const profileIconImg = document.querySelector('.profile-img');
                const profileForm = document.getElementById('profileForm');

                if (profileIconImg && profileForm && profileWrap) {
                    profileIconImg.addEventListener('click', e => {
                        e.stopPropagation();
                        profileForm.classList.toggle('d-none');
                    });

                    document.addEventListener('click', e => {
                        if (!profileWrap.contains(e.target)) profileForm.classList.add('d-none');
                    });
                }

                function enableEdit(fieldId, saveBtnId) {
                    const field = document.getElementById(fieldId);
                    const saveBtn = document.getElementById(saveBtnId);
                    if (field && saveBtn) {
                        field.removeAttribute('readonly');
                        field.focus();
                        saveBtn.classList.remove('d-none');
                    }
                }

                function saveField(fieldId, saveBtnId) {
                    const field = document.getElementById(fieldId);
                    const saveBtn = document.getElementById(saveBtnId);
                    if (field && saveBtn) {
                        field.setAttribute('readonly', true);
                        saveBtn.classList.add('d-none');
                    }
                }

                document.querySelectorAll('.btn-edit').forEach(btn => {
                    const targetField = btn.dataset.field;
                    const saveBtnId = btn.dataset.savebtn;
                    btn.addEventListener('click', () => enableEdit(targetField, saveBtnId));
                });

                document.querySelectorAll('.btn-save').forEach(btn => {
                    const targetField = btn.dataset.field;
                    btn.addEventListener('click', () => saveField(targetField, btn.id));
                });

                // ======== مدیریت تسک‌ها ========
                const taskTemplate = document.getElementById('task-template');
                const tasksContainer = document.querySelector('.tasks-column');
                const taskForm = document.getElementById('taskForm');
                const taskModalEl = document.getElementById('taskModal');
                const taskModal = new bootstrap.Modal(taskModalEl);
                let currentEditingTaskId = null;

                taskModalEl.addEventListener('hidden.bs.modal', () => taskForm.reset());

                const saveTaskToStorage = task => {
                    const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
                    const idx = tasks.findIndex(t => t.id === task.id);
                    if (idx !== -1) tasks[idx] = task;
                    else tasks.push(task);
                    localStorage.setItem('tasks', JSON.stringify(tasks));
                };

                const removeTaskFromStorage = id => {
                    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
                    tasks = tasks.filter(t => t.id !== id);
                    localStorage.setItem('tasks', JSON.stringify(tasks));
                };

                const createTaskElement = taskData => {
                    const newTask = taskTemplate.querySelector('.task-card').cloneNode(true);
                    newTask.classList.remove('d-none');
                    newTask.dataset.id = taskData.id;
                    newTask.dataset.priority = taskData.priority;

                    // عنوان و توضیح
                    newTask.querySelector('.task-title').textContent = taskData.title;
                    const words = taskData.description.trim().split(/\s+/);
                    newTask.querySelector('.task-text').textContent = words.slice(0, 4).join(' ') + (words.length > 4 ? '...' : '');

                    // Popover متن
                    const taskTextEl = newTask.querySelector('.task-text');
                    const popoverDesc = new bootstrap.Popover(taskTextEl, { trigger: 'manual', html: true, content: taskData.description, placement: 'top' });

                    // Popover زمان
                    const timeIcon = newTask.querySelector('.task-icon i.ri-time-fill');
                    const popoverTime = new bootstrap.Popover(timeIcon, { trigger: 'manual', html: true, content: `<br>تاریخ: ${taskData.date} <br>شروع: ${taskData.startTime} <br>پایان: ${taskData.endTime}`, placement: 'top' });

                    // باز و بسته شدن Popover ها
                    taskTextEl.addEventListener('click', e => {
                        e.stopPropagation();
                        document.querySelectorAll('.task-text').forEach(el => { if (el !== taskTextEl) bootstrap.Popover.getInstance(el)?.hide(); });
                        popoverDesc.toggle();
                    });

                    timeIcon.addEventListener('click', e => {
                        e.stopPropagation();
                        document.querySelectorAll('.task-icon i.ri-time-fill').forEach(el => { if (el !== timeIcon) bootstrap.Popover.getInstance(el)?.hide(); });
                        popoverTime.toggle();
                    });

                    // کلیک بیرون از Popover ها
                    document.addEventListener('click', () => {
                        document.querySelectorAll('.task-text').forEach(el => bootstrap.Popover.getInstance(el)?.hide());
                        document.querySelectorAll('.task-icon i.ri-time-fill').forEach(el => bootstrap.Popover.getInstance(el)?.hide());
                    });

                    // Checkbox تکمیل تسک
                    const checkbox = newTask.querySelector('.complete-task input');
                    checkbox.checked = taskData.completed || false;
                    if (checkbox.checked) newTask.classList.add('completed');
                    checkbox.addEventListener('change', () => {
                        newTask.classList.toggle('completed');
                        taskData.completed = checkbox.checked;
                        saveTaskToStorage(taskData);
                        applyCurrentFilter();
                        updateActivityProgress();
                    });


                    // ویرایش تسک
                    newTask.querySelector('.edit-task').addEventListener('click', () => {
                        currentEditingTaskId = taskData.id;
                        document.getElementById('taskTitle').value = taskData.title;
                        document.getElementById('taskDescription').value = taskData.description;
                        document.getElementById('persianDate').value = taskData.date;
                        document.getElementById('startTime').value = taskData.startTime;
                        document.getElementById('endTime').value = taskData.endTime;
                        document.getElementById('taskPriority').value = taskData.priority;
                        taskModal.show();
                    });

                    // حذف تسک
                    newTask.querySelector('.delete-task').addEventListener('click', () => {
                        removeTaskFromStorage(taskData.id);
                        newTask.remove();
                        updateActivityProgress();
                    });

                    tasksContainer.appendChild(newTask);

                    // مرتب سازی بر اساس اهمیت
                    const sorted = Array.from(tasksContainer.children).sort((a, b) => b.dataset.priority - a.dataset.priority);
                    sorted.forEach(task => tasksContainer.appendChild(task));
                };

                const loadTasksFromStorage = () => {
                    const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
                    tasks.forEach(task => createTaskElement(task));
                    applyCurrentFilter();
                    updateActivityProgress();
                };

                const applyCurrentFilter = () => {
                    const select = document.querySelector('.custom-select');
                    if (!select || !select.filterTasks) return;
                    select.filterTasks();
                };

                if (taskForm) {
                    taskForm.addEventListener('submit', e => {
                        e.preventDefault();

                        const priority = parseInt(document.getElementById('taskPriority').value) || 0;
                        const exists = Array.from(document.querySelectorAll('.task-card')).some(card => parseInt(card.dataset.priority) === priority);
                        if (priority > 0 && exists && !currentEditingTaskId) { alert('این اهمیت قبلاً پر شده است!'); return; }

                        const taskData = {
                            id: currentEditingTaskId || Date.now(),
                            title: document.getElementById('taskTitle').value,
                            description: document.getElementById('taskDescription').value,
                            date: document.getElementById('persianDate').value,
                            startTime: document.getElementById('startTime').value,
                            endTime: document.getElementById('endTime').value,
                            priority: priority,
                            completed: false
                        };

                        saveTaskToStorage(taskData);

                        if (currentEditingTaskId) {
                            document.querySelectorAll('.task-card').forEach(card => { if (parseInt(card.dataset.id) === currentEditingTaskId) card.remove(); });
                            currentEditingTaskId = null;
                        }

                        createTaskElement(taskData);
                        taskModal.hide();
                        taskForm.reset();
                        applyCurrentFilter();
                        updateActivityProgress();
                    });
                }

                // ======== فیلترها ========
                document.querySelectorAll('.custom-select').forEach(select => {
                    const selected = select.querySelector('.selected-option');
                    const options = select.querySelector('.options-list');

                    const filterTasks = () => {
                        const currentFilter = selected.textContent.trim();
                        document.querySelectorAll('.task-card').forEach(card => {
                            if (currentFilter === 'تسک جاری') card.style.display = card.classList.contains('completed') ? 'none' : 'flex';
                            else if (currentFilter === 'تسک پایان یافته') card.style.display = card.classList.contains('completed') ? 'flex' : 'none';
                        });
                    };

                    selected.addEventListener('click', e => {
                        e.stopPropagation();
                        options.style.display = options.style.display === 'block' ? 'none' : 'block';
                    });

                    select.querySelectorAll('.option-item').forEach(option => {
                        option.addEventListener('click', () => {
                            selected.textContent = option.textContent;
                            options.style.display = 'none';
                            filterTasks();
                        });
                    });

                    document.addEventListener('click', () => options.style.display = 'none');
                    select.filterTasks = filterTasks;
                });

                // ======== Activity Progress (کل تسک‌ها) ========
                function updateActivityProgress() {
                    const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
                    if (tasks.length === 0) {
                        document.getElementById('tasksCompletedText').textContent = '0 تسک تکمیل شده';
                        document.getElementById('tasksCompletedPercent').textContent = '0%';
                        document.getElementById('activityProgressBar').style.width = '0%';
                        return;
                    }
                    const completedTasks = tasks.filter(t => t.completed);
                    const percent = Math.round((completedTasks.length / tasks.length) * 100);
                    document.getElementById('tasksCompletedText').textContent = `${completedTasks.length} از ${tasks.length} تسک تکمیل شده`;
                    document.getElementById('tasksCompletedPercent').textContent = `${percent}%`;
                    document.getElementById('activityProgressBar').style.width = percent + '%';
                }

                loadTasksFromStorage();

            });


        </script>
    @endpush
@endsection