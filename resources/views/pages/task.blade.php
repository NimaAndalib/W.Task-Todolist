@extends('layouts.app')

@section('title', 'taskbar')
@push('page-css')
    <link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
@endpush
@section('content')
    <div class="task-container">
        <!-- هدر -->
        <div class="dashboard-header row align-items-center pb-3">
            <div class="col-md-6 col-12 d-flex align-items-center">
                <!-- profile -->
                <div class="profile-wrap position-relative w-100">
                    <div class="profile-icon me-3">
                        <img src="{{ auth()->user()->profile_image ? asset('storage/profiles/' . auth()->user()->profile_image) : asset('img/prof-icon.png') }}"
                            class="rounded-circle profile-img" alt="پروفایل کاربر"
                            style="cursor:pointer; width:50px; height:50px;">
                        <span class="ms-2 d-none d-sm-inline-block text-white fw-bold">
                            {{ auth()->user()->name ?? 'کاربر' }}
                        </span>
                    </div>

                    <div id="profileForm" class="card shadow p-3 bg-dark text-white position-absolute mt-2 d-none w-100"
                        style="left:0; top:100%; z-index:1055;">

                        <div class="mb-3">
                            <label class="form-label">نام</label>
                            <div class="input-group d-flex">
                                <input type="text" class="form-control bg-dark text-white" id="nameField"
                                    value="{{ auth()->user()->name }}" readonly>
                                <button class="btn btn-outline-secondary btn-edit" data-field="nameField"
                                    data-savebtn="saveNameBtn">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button id="saveNameBtn" class="btn btn-success btn-save d-none"
                                    data-field="nameField">ذخیره</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ایمیل</label>
                            <div class="input-group">
                                <input type="email" class="form-control bg-dark text-white" id="emailField"
                                    value="{{ auth()->user()->email }}" readonly>
                                <button class="btn btn-outline-secondary btn-edit" data-field="emailField"
                                    data-savebtn="saveEmailBtn">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button id="saveEmailBtn" class="btn btn-success btn-save d-none"
                                    data-field="emailField">ذخیره</button>
                            </div>
                        </div>

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
                <div class="navbar navbar-expand navbar-expand-md col-6 d-flex justify-content-end">
                    <button class="navbar-toggler text-white" data-bs-toggle="collapse" data-bs-target="#collapse1">
                        <span class="navbar-toggler-icon text-white"></span>
                    </button>
                </div>
            </div>
            <div class="col-md-6 col-12 d-flex justify-content-center justify-content-md-start mt-3 mt-sm-0"
                style="direction: ltr;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb-nav list-inline">
                        <li class="list-inline-item"><a style="color: rgba(255, 255, 255, 0.7);text-decoration: none;"
                                href="{{ route('dashboard') }}">خانه</a></li>
                        <li class="list-inline-item"><span class="mx-2">/</span></li>
                        <li class="list-inline-item" style="color: rgba(255, 255, 255, 0.7);">داشبورد</li>
                        <li class="list-inline-item"><span class="mx-2">/</span></li>
                        <li class="list-inline-item"><span style="color: rgba(255, 255, 255, 0.7);"
                                class="task-date d-block"></span></li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="dashboard-actions col-12 mt-4">
            <div class="col-md-12">
                <div class="row align-items-center d-flex justify-content-between">
                    <div class="col-6">
                        <div class="custom-select-wrapper">
                            <div class="custom-select py-2 btn">
                                <div class="selected-option text-white text-center" data-value="current">
                                    <span>تسک جاری</span>
                                    <i class="ri-arrow-down-s-line ms-2"></i>
                                </div>
                                <ul class="options-list d-none">
                                    <li class="option-item" data-value="all">همه تسک‌ها</li>
                                    <li class="option-item" data-value="current">تسک جاری</li>
                                    <li class="option-item" data-value="completed">تسک پایان یافته</li>
                                    <li class="option-item" data-value="priority">بر اساس اولویت</li>
                                    <li class="option-item" data-value="date">بر اساس تاریخ</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <button class="btn btn-create-task" data-bs-toggle="modal" data-bs-target="#taskModal">
                            ساخت تسک
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-4 tasks-column">
            <!-- تسک‌ها اینجا اضافه می‌شوند -->
            @php
                $initialTasks = $tasks ?? auth()->user()->tasks()->latest()->get();
            @endphp

            @foreach ($initialTasks as $task)
                <div class="task-card" data-id="{{ $task->id }}" data-priority="{{ $task->priority }}"
                    data-date="{{ $task->date }}">
                    <div class="task-body w-100 d-flex flex-column flex-sm-row justify-content-between align-items-center">
                        <div class="task-right__box d-flex align-items-center">
                            <div class="form-check complete-task">
                                <input type="checkbox" class="form-check-input" {{ $task->completed ? 'checked' : '' }}>
                            </div>
                            <h6 class="task-title">{{ $task->title }}</h6>
                        </div>
                        <div class="task-center__box">
                            <p class="task-text">{{ \Illuminate\Support\Str::words($task->description ?? '', 4, '...') }}</p>
                        </div>
                        <div class="task-end__box gap-2 d-flex align-items-center">
                            <div class="task-icon">
                                <i class="ri-time-fill"></i>
                                <span class="time-text" data-bs-toggle="popover" data-bs-trigger="hover"
                                    title="زمان شروع"></span>
                            </div>
                            <div class="task-icon edit-task" data-id="{{ $task->id }}">
                                <i class="ri-edit-2-fill"></i>
                            </div>

                            <button type="button" class="task-icon delete-task bg-transparent text-white border-0"
                                data-id="{{ $task->id }}">
                                <i class="ri-delete-bin-5-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <!-- مودال ساخت تسک -->
    <div class="modal fade modal-dark" id="taskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="modalTitle">ساخت تسک جدید</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="بستن"></button>
                </div>
                <div class="modal-body">
                    <form id="taskForm" autocomplete="off">
                        @CSRF
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
                                <input type="text" id="persianDate" class="form-control persian-date-input"
                                    placeholder="1404/01/04" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">ساعت شروع</label>
                                <input type="text" id="startTime" class="form-control" placeholder="13:00" required>
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
                            <button type="button" class="btn btn-outline-light me-2" data-bs-dismiss="modal"
                                id="openTaskModal">انصراف</button>
                            <button type="submit" class="btn btn-primary">ذخیره تسک</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- تمپلیت تسک --> 
    <div id="task-template" class="task-template d-none">
        <div class="task-card">
            <div class="task-body w-100 d-flex flex-column flex-sm-row justify-content-between align-items-center">
                <div class="task-right__box d-flex align-items-center">
                    <div class="form-check complete-task">
                        <input type="checkbox" class="form-check-input">
                    </div>
                    <h6 class="task-title"></h6>
                </div>
                <div class="task-center__box">
                    <p class="task-text"></p>
                </div>
                <div class="task-end__box gap-2 d-flex align-items-center">
                    <div class="task-icon">
                        <i class="ri-time-fill"></i>
                    </div>
                    <div class="task-icon edit-task">
                        <i class="ri-edit-2-fill"></i>
                    </div>
                    <div class="task-icon delete-task bg-transparent border-0 text-white">
                        <i class="ri-delete-bin-5-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            window.APP_ROUTES = {
                taskStore: '{{ route("task.store") }}',
                taskUpdate: (id) => `{{ url('task') }}/${id}`,
                taskDestroy: (id) => `{{ url('task') }}/${id}`
            };
            window.CSRF_TOKEN = '{{ csrf_token() }}';
            window.initialTasks = {!! json_encode($initialTasks->map(function ($task) {
            return [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'date' => $task->date,
                'startTime' => $task->start_time,
                'priority' => $task->priority,
                'completed' => $task->completed,
            ];
        })) !!};

            const toggler = document.querySelector('.navbar-toggler');
            const navbar = document.getElementById('navbar');

            toggler.addEventListener('click', () => {
                navbar.classList.toggle('active');
                // اگه خواستی خود دکمه هم کلاس بگیره
                toggler.classList.toggle('active');
            });

        </script>
        <script src="{{ asset('js/task.js') }}"></script>
    @endpush
@endsection