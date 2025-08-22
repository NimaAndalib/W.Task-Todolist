document.addEventListener('DOMContentLoaded', function () {

    // ======== نمایش تاریخ امروز به فارسی ========
    const updateTodayDate = () => {
        const today = new Date();
        const persianDate = today.toLocaleDateString('fa-IR', { year: 'numeric', month: 'numeric', day: 'numeric' });
        document.querySelectorAll('.task-date').forEach(el => el.textContent = persianDate);
    };
    updateTodayDate();

    // ======== یادداشت روزانه ========
    const noteText = document.getElementById('noteText');
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const deleteBtn = document.getElementById('deleteBtn');
    const timeStamp = document.getElementById('timeStamp');

    let firstEdit = true;

    function timeAgo(savedTime) {
        const now = new Date();
        const diff = Math.floor((now - new Date(savedTime)) / 1000);
        if (diff < 60) return `${diff} ثانیه پیش`;
        if (diff < 3600) return `${Math.floor(diff/60)} دقیقه پیش`;
        if (diff < 86400) return `${Math.floor(diff/3600)} ساعت پیش`;
        return `${Math.floor(diff/86400)} روز پیش`;
    }

    const savedNote = localStorage.getItem('todayNote');
    const savedTime = localStorage.getItem('noteTime');

    if(savedNote) {
        noteText.textContent = savedNote;
        noteText.style.color = "#fff";
        firstEdit = false;
    } else {
        noteText.textContent = 'تایپ کنید...';
        noteText.style.color = "#ccc";
    }

    if(savedTime) timeStamp.textContent = timeAgo(savedTime);

    setInterval(() => {
        const storedTime = localStorage.getItem('noteTime');
        if(storedTime) timeStamp.textContent = timeAgo(storedTime);
    }, 60000);

    editBtn.addEventListener('click', () => {
        noteText.contentEditable = "true";
        saveBtn.style.display = "inline-block";
        noteText.focus();
        if(firstEdit) {
            noteText.textContent = '';
            noteText.style.color = "#fff";
            firstEdit = false;
        }
    });

    saveBtn.addEventListener('click', () => {
        const now = new Date();
        localStorage.setItem('todayNote', noteText.textContent);
        localStorage.setItem('noteTime', now.toISOString());
        timeStamp.textContent = timeAgo(now);
        noteText.contentEditable = "false";
        saveBtn.style.display = "none";
    });

    deleteBtn.addEventListener('click', () => {
        noteText.textContent = 'تایپ کنید...';
        noteText.style.color = "#ccc";
        firstEdit = true;
        localStorage.removeItem('todayNote');
        localStorage.removeItem('noteTime');
        timeStamp.textContent = '';
        saveBtn.style.display = "none";
        noteText.contentEditable = "false";
    });

    // ======== انتخابگر سفارشی ========
    document.querySelectorAll('.custom-select').forEach(select => {
        const selected = select.querySelector('.selected-option');
        const options = select.querySelector('.options-list');

        const filterTasks = () => {
            const currentFilter = selected.textContent.trim();
            document.querySelectorAll('.task-card').forEach(card => {
                const completed = card.classList.contains('completed');
                const priority = parseInt(card.dataset.priority) || 0;

                if(priority === 0) { card.style.display = 'none'; return; }

                if(currentFilter === 'تسک جاری') card.style.display = completed ? 'none' : 'flex';
                else if(currentFilter === 'تسک پایان یافته') card.style.display = (completed && priority > 0) ? 'flex' : 'none';
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

    // ======== Activity Progress ========
    function updateActivityProgress() {
        const tasks = Array.from(document.querySelectorAll('.task-card'))
                            .filter(task => task.closest('#task-template') === null && !task.classList.contains('d-none'));
        
        if(tasks.length === 0){
            document.getElementById('tasksCompletedText').textContent = '0 تسک تکمیل شده';
            document.getElementById('tasksCompletedPercent').textContent = '0%';
            document.getElementById('activityProgressBar').style.width = '0%';
            return;
        }

        const completedTasks = tasks.filter(task => task.classList.contains('completed'));
        const percent = Math.round((completedTasks.length / tasks.length) * 100);
        document.getElementById('tasksCompletedText').textContent = `${completedTasks.length} از ${tasks.length} تسک تکمیل شده`;
        document.getElementById('tasksCompletedPercent').textContent = `${percent}%`;
        document.getElementById('activityProgressBar').style.width = percent + '%';
    }

    // ======== مدیریت تسک‌ها ========
    const setupTaskManagement = () => {
        const taskTemplate = document.getElementById('task-template');
        const tasksContainer = document.querySelector('.tasks-column');
        const taskForm = document.getElementById('taskForm');
        const taskModalEl = document.getElementById('taskModal');
        const taskModal = new bootstrap.Modal(taskModalEl);
        let currentEditingTaskId = null;

        taskModalEl.addEventListener('hidden.bs.modal', () => taskForm.reset());

        const createTaskElement = taskData => {
            const newTask = taskTemplate.querySelector('.task-card').cloneNode(true);
            newTask.classList.remove('d-none');
            newTask.dataset.id = taskData.id;
            newTask.dataset.priority = taskData.priority;

            newTask.querySelector('.task-title').textContent = taskData.title;
            const words = taskData.description.trim().split(/\s+/);
            newTask.querySelector('.task-text').textContent = words.slice(0,4).join(' ') + (words.length>4?'...':'');

            const taskTextEl = newTask.querySelector('.task-text');
            const popoverDesc = new bootstrap.Popover(taskTextEl, { trigger:'manual', html:true, content:taskData.description, placement:'top' });
            const timeIcon = newTask.querySelector('.task-icon i.ri-time-fill');
            const popoverTime = new bootstrap.Popover(timeIcon, { trigger:'manual', html:true, content:`<br>تاریخ: ${taskData.date} <br>شروع: ${taskData.startTime} <br>پایان: ${taskData.endTime}`, placement:'top' });

            taskTextEl.addEventListener('click', e => {
                e.stopPropagation();
                document.querySelectorAll('.task-text').forEach(other => { if(other!==taskTextEl) bootstrap.Popover.getInstance(other)?.hide(); });
                popoverDesc.toggle();
            });
            timeIcon.addEventListener('click', e => {
                e.stopPropagation();
                document.querySelectorAll('.task-icon i.ri-time-fill').forEach(other => { if(other!==timeIcon) bootstrap.Popover.getInstance(other)?.hide(); });
                popoverTime.toggle();
            });
            document.addEventListener('click', () => {
                document.querySelectorAll('.task-text').forEach(el => bootstrap.Popover.getInstance(el)?.hide());
                document.querySelectorAll('.task-icon i.ri-time-fill').forEach(el => bootstrap.Popover.getInstance(el)?.hide());
            });

            const checkbox = newTask.querySelector('.complete-task input');
            checkbox.checked = taskData.completed || false;
            if(checkbox.checked) newTask.classList.add('completed');
            checkbox.addEventListener('change', () => {
                newTask.classList.toggle('completed');
                taskData.completed = checkbox.checked;
                saveTaskToStorage(taskData);
                applyCurrentFilter();
                updateActivityProgress();
            });

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

            newTask.querySelector('.delete-task').addEventListener('click', () => {
                removeTaskFromStorage(taskData.id);
                newTask.remove();
                updateActivityProgress();
            });

            if(taskData.priority>0){
                tasksContainer.appendChild(newTask);
                const sorted = Array.from(tasksContainer.children).sort((a,b)=>b.dataset.priority-a.dataset.priority);
                sorted.forEach(task => tasksContainer.appendChild(task));
            }
        };

        const saveTaskToStorage = task => {
            const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
            const idx = tasks.findIndex(t => t.id === task.id);
            if(idx!==-1) tasks[idx]=task;
            else tasks.push(task);
            localStorage.setItem('tasks', JSON.stringify(tasks));
        };

        const removeTaskFromStorage = id => {
            let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
            tasks = tasks.filter(t => t.id!==id);
            localStorage.setItem('tasks', JSON.stringify(tasks));
        };

        const loadTasksFromStorage = () => {
            const tasks = JSON.parse(localStorage.getItem('tasks')) || [];
            tasks.forEach(task => createTaskElement(task));
            applyCurrentFilter();
            updateActivityProgress();
        };

        const applyCurrentFilter = () => {
            const select = document.querySelector('.custom-select');
            if(select && select.filterTasks) select.filterTasks();
        };

        if(taskForm){
            taskForm.addEventListener('submit', e => {
                e.preventDefault();
                const priority = parseInt(document.getElementById('taskPriority').value) || 0;
                if(priority>0){
                    const exists = Array.from(document.querySelectorAll('.task-card')).some(card => parseInt(card.dataset.priority)===priority);
                    if(exists){ alert('این اهمیت قبلاً پر شده است!'); return; }
                }

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

                if(currentEditingTaskId){
                    document.querySelectorAll('.task-card').forEach(card => { if(parseInt(card.dataset.id)===currentEditingTaskId) card.remove(); });
                    currentEditingTaskId=null;
                }

                createTaskElement(taskData);
                taskModal.hide();
                taskForm.reset();
                applyCurrentFilter();
                updateActivityProgress();
            });
        }

        loadTasksFromStorage();
    };

    setupTaskManagement();

    // ======== Flatpickr ========
    if(typeof flatpickr!=='undefined'){
        flatpickr("#persianDate",{locale:"fa", dateFormat:"Y/m/d"});
        flatpickr("#startTime, #endTime",{enableTime:true,noCalendar:true,dateFormat:"H:i",time_24hr:true});
    }

});
