@extends('layouts.app')

@section('title', 'dashboard')

@section('content')
    <div class="dashboard-container">
        <!-- هدر -->
        <div class="dashboard-header row align-items-center py-3">
            <div class="col-md-6 d-flex align-items-center">
                <div class="profile-icon me-3">
                    <img src="{{ asset('img/prof-icon.png') }}" alt="پروفایل" class="rounded-circle profile-img">
                </div>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb-nav list-inline">
                        <li><a href="{{ route('dashboard') }}">خانه</a></li>
                        <span class="mx-2">/</span>
                        <li><a href="#">داشبورد</a></li>
                        <span class="mx-2">/</span>
                        <li><span class="task-date d-block" style="font-size: 18px; margin: 2px 0 0;"></span></li>
                    </ul>
                </nav>
            </div>
        </div>



        <!-- محتوای اصلی -->
        <div class="dashboard-content row mt-0 justify-content-between">
            <div class="right-box col-8">
                <!-- عنوان و توضیحات -->
                <div class="dashboard-title col-md-12 mt-2">
                    <div class="col-md-8">
                        <h1 class="gradient-title">کـــارتـــو ســـاده بـســـاز!</h1>
                        <p class="description-text">لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده
                            از
                            طراحان
                            گرافیک است</p>
                    </div>
                </div>

                <!-- فیلترها و دکمه‌ها -->
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
                            <div class="col-md-6"></div>
                            <div class="col-md-3 d-flex justify-content-evenly">
                                <button class="btn btn-create-task" data-bs-toggle="modal" data-bs-target="#taskModal">
                                    ساخت تسک
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ستون نمایش تسک‌ها -->
                <div class="col-md-12 mt-4 tasks-column">
                    <!-- تسک‌ها اینجا اضافه می‌شوند -->
                </div>
            </div>
            <div class="left-box col-4">
                <div class="col-12 sidebar-column d-flex flex-column justify-content-between align-items-center mt-4">
                    <section dir="rtl">
                        <div class="card sidebar-card rounded-4 text-white"
                            style="width:400px; height: 19rem; background-color:rgba(36, 31, 55, 0.38)">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h6 class="card-title">متن روزانه</h6>
                                    <button id="editBtn" class="btn btn-dark text-white rounded-pill py-2">
                                        <i class="ri-edit-2-fill"></i>
                                    </button>
                                </div>
                                <p id="noteText" class="card-text" contenteditable="false" style="color: #ccc;">تایپ کنید...
                                </p>
                                <button id="saveBtn" class="btn btn-success mt-2" style="display:none;">ذخیره</button>
                            </div>
                            <div class="card-footer border-0 d-flex justify-content-between align-items-center"
                                style="background-color: transparent;">
                                <div class="card-footer__right d-flex align-items-center">
                                    <i class="ri-notification-3-fill text-primary"></i>
                                    <span id="timeStamp" class="ms-2"></span>
                                </div>
                                <div class="card-footer__left">
                                    <button id="deleteBtn" class="btn btn-dark text-white fw-bold">
                                        حذف
                                        <i class="ri-square-root"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section dir="rtl">
                        <div id="activityCard" class="card sidebar-card mt-lg-5 rounded-4 text-white shadow-lg"
                            style="width:400px; background:rgba(36, 31, 55, 0.38)">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="card-title m-0">فعالیت ها</h6>
                                    <i class="ri-bar-chart-2-fill" style="font-size: 24px;"></i>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span id="tasksCompletedText">0 تسک تکمیل شده</span>
                                    <span id="tasksCompletedPercent">0%</span>
                                </div>
                                <div class="progress rounded-pill"
                                    style="height: 12px; background-color: rgba(255,255,255,0.2);">
                                    <div id="activityProgressBar"
                                        class="progress-bar progress-bar-striped progress-bar-animated rounded-pill"
                                        style="width: 0%; background: linear-gradient(90deg, #ff6a00, #ee0979);"></div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
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
                                    placeholder="1404/10/4" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">ساعت شروع</label>
                                <input type="text" id="startTime" class="form-control" placeholder="1:00" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">ساعت پایان</label>
                                <input type="text" id="endTime" class="form-control" placeholder="1:30" required>
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
@endsection