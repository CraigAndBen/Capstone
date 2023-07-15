<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\NurseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::get('/user/dashboard', function () {
    return view('user_dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/user/logout', [UsersController::class, 'userLogout'])->name('user.logout');

// User Appointment
Route::get('/user/show/appointment', [AppointmentController::class, 'showAppointment'])->name('user.show.appointment');
Route::get('/user/appointment', [AppointmentController::class, 'appointment'])->name('user.appointment');
Route::get('/user/appointment/confirmed', [AppointmentController::class, 'confirmedAppointmentList'])->name('user.confirmed.appointment');

Route::get('/user/appointment/done', [AppointmentController::class, 'doneAppointmentList'])->name('user.done.appointment');
Route::get('/user/appointment/cancelled', [AppointmentController::class, 'cancelledAppointmentList'])->name('user.cancelled.appointment');
Route::post('/user/create/appointment', [AppointmentController::class, 'createAppointment'])->name('user.create.appointment');
Route::post('/user/update/appointment', [AppointmentController::class, 'updateAppointment'])->name('user.update.appointment');
Route::post('/user/cancel/appointment', [AppointmentController::class, 'cancelAppointment'])->name('user.cancel.appointment');

// User Notification
Route::get('/user/notification', [AppointmentController::class, 'notification'])->name('user.notification');
Route::post('/user/notification/read', [AppointmentController::class, 'notificationRead'])->name('user.notification.read');

//profile
Route::get('/user/profile', [ProfileController::class, 'profile'])->name('user.profile');
Route::get('/user/profile/password', [ProfileController::class, 'passwordProfile'])->name('user.profile.password');
Route::post('/user/profile/update', [ProfileController::class, 'profileUpdate'])->name('user.profile.update');
Route::post('/user/profile/update/password', [ProfileController::class, 'updatePassword'])->name('user.password.update');


require __DIR__ . '/auth.php';

Route::middleware('auth', 'role:nurse')->group(function () {

    // Dashboard
    Route::get('/nurse/dashboard', [NurseController::class, 'dashboard'])->name('nurse.dashboard');

    // Profile
    Route::get('/nurse/profile', [NurseController::class, 'profile'])->name('nurse.profile');
    Route::get('/nurse/profile/password', [NurseController::class, 'passwordProfile'])->name('nurse.profile.password');
    Route::post('/nurse/profile/update', [NurseController::class, 'profileUpdate'])->name('nurse.profile.update');
    Route::post('/nurse/profile/update/password', [NurseController::class, 'updatePassword'])->name('nurse.password.update');


    // Patient
    Route::get('/nurse/patient', [NurseController::class, 'patientList'])->name('nurse.patient');

    // Logout
    Route::get('/nurse/logout', [NurseController::class, 'nurseLogout'])->name('nurse.logout');

});

// Doctor

Route::middleware('auth', 'role:doctor')->group(function () {

    // Dashboard
    Route::get('/doctor/dashboard', [DoctorController::class, 'dashboard'])->name('doctor.dashboard');

    // Profile
    Route::get('/doctor/profile', [DoctorController::class, 'profile'])->name('doctor.profile');
    Route::get('/doctor/profile/password', [DoctorController::class, 'passwordProfile'])->name('doctor.profile.password');
    Route::post('/doctor/profile/update', [DoctorController::class, 'profileUpdate'])->name('doctor.profile.update');
    Route::post('/doctor/profile/update/password', [DoctorController::class, 'updatePassword'])->name('doctor.password.update');

    // Appointment
    Route::get('/doctor/appointment', [DoctorController::class, 'appointment'])->name('doctor.appointment');
    Route::get('/doctor/appointment/confirmed', [DoctorController::class, 'confirmedAppointmentList'])->name('doctor.appointment.confirmed');
    Route::get('/doctor/appointment/done', [DoctorController::class, 'doneAppointmentList'])->name('doctor.appointment.done');
    Route::post('/doctor/appointment/confirm', [DoctorController::class, 'confirmedAppointment'])->name('doctor.confirm.appointment');
    Route::post('/doctor/appointment/finish', [DoctorController::class, 'doneAppointment'])->name('doctor.finish.appointment');

    // Patient
    Route::get('/doctor/patient', [DoctorController::class, 'patientList'])->name('doctor.patient');
    Route::post('/doctor/patient/update', [DoctorController::class, 'patientUpdate'])->name('doctor.patient.update');

    // Logout
    Route::get('/doctor/logout', [DoctorController::class, 'doctorLogout'])->name('doctor.logout');
});

// Admin

Route::middleware('auth', 'role:admin')->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Profile
    Route::get('/admin/profile', [AdminController::class, 'edit'])->name('admin.profile.edit');
    Route::patch('/admin/profile/update', [AdminController::class, 'update'])->name('admin.profile.update');
    Route::put('/admin/profile/update', [AdminController::class, 'updatePassword'])->name('admin.password.update');

    // Patient
    Route::get('/admin/patient', [AdminController::class, 'patientList'])->name('admin.patient');
    Route::post('/admin/patient/store', [AdminController::class, 'patientStore'])->name('admin.patient.store');
    Route::post('/admin/patient/update', [AdminController::class, 'patientUpdate'])->name('admin.patient.update');

    // Logout
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');
});

Route::middleware('auth', 'role:super_admin')->group(function () {

    Route::get('/super_admin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');

    // Profile
    Route::get('/super_admin/profile', [SuperAdminController::class, 'edit'])->name('superadmin.profile.edit');
    Route::patch('/super_admin/profile', [SuperAdminController::class, 'update'])->name('superadmin.profile.update');
    Route::put('/super_admin/profile/update', [SuperAdminController::class, 'updatePassword'])->name('superadmin.password.update');

    // Doctor
    Route::get('/super_admin/doctor', [SuperAdminController::class, 'doctor'])->name('superadmin.doctor');
    Route::post('/super_admin/doctor/create', [SuperAdminController::class, 'createDoctor'])->name('superadmin.store.doctor');
    Route::post('/super_admin/doctor/update', [SuperAdminController::class, 'updateDoctorInfo'])->name('superadmin.update.doctor');
    Route::post('/super_admin/doctor/update/status', [SuperAdminController::class, 'updateDoctorStatus'])->name('superadmin.doctor.update.status');
    Route::post('/super_admin/doctor/update/password', [SuperAdminController::class, 'updateDoctorPassword'])->name('superadmin.doctor.password.update');

    // Nurse
    Route::get('/super_admin/nurse', [SuperAdminController::class, 'nurse'])->name('superadmin.nurse');
    Route::post('/super_admin/nurse/create', [SuperAdminController::class, 'createNurse'])->name('superadmin.store.nurse');
    Route::post('/super_admin/nurse/update', [SuperAdminController::class, 'updateNurseInfo'])->name('superadmin.update.nurse');
    Route::post('/super_admin/nurse/update/status', [SuperAdminController::class, 'updateNurseStatus'])->name('superadmin.nurse.update.status');
    Route::post('/super_admin/nurse/update/password', [SuperAdminController::class, 'updateNursePassword'])->name('superadmin.nurse.password.update');

    // User
    Route::get('/super_admin/user', [SuperAdminController::class, 'user'])->name('superadmin.user');
    Route::post('/super_admin/user/create', [SuperAdminController::class, 'createUser'])->name('superadmin.store.user');
    Route::post('/super_admin/user/update', [SuperAdminController::class, 'updateUserInfo'])->name('superadmin.update.user');
    Route::post('/super_admin/user/update/status', [SuperAdminController::class, 'updateUserStatus'])->name('superadmin.user.update.status');
    Route::post('/super_admin/nurse/update/password', [SuperAdminController::class, 'updateUserPassword'])->name('superadmin.user.password.update');

    // Admin
    Route::get('/super_admin/admin', [SuperAdminController::class, 'admin'])->name('superadmin.admin');
    Route::post('/super_admin/admin/create', [SuperAdminController::class, 'createAdmin'])->name('superadmin.store.admin');
    Route::post('/super_admin/admin/update', [SuperAdminController::class, 'updateAdminInfo'])->name('superadmin.update.admin');
    Route::post('/super_admin/admin/update/status', [SuperAdminController::class, 'updateAdminStatus'])->name('superadmin.admin.update.status');
    Route::post('/super_admin/nurse/update/password', [SuperAdminController::class, 'updateAdminPassword'])->name('superadmin.user.password.update');


    Route::get('/super_admin/logout', [SuperAdminController::class, 'superAdminLogout'])->name('superadmin.logout');
});