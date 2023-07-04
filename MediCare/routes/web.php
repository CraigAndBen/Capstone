<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
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
    
Route::get('/user/appointment', [AppointmentController::class, 'show'])->name('user.appointment');
Route::get('/user/create/appointment', [AppointmentController::class, 'createAppointment'])->name('user.create.appointment');Route::get('user/doctors/{specialtyId}', [AppointmentController::class, 'getUpdatedDoctor'])->name('user.get.getUpdatedDoctor');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth','role:nurse')->group(function(){
    Route::get('/nurse/dashboard', [UsersController::class, 'NurseDashboard'])->name('nurse.dashboard');
});

// Doctor

Route::middleware('auth','role:doctor')->group(function(){

    Route::get('/doctor/dashboard', [DoctorController::class, 'dashboard'])->name('doctor.dashboard');

    Route::get('/doctor/profile', [DoctorController::class, 'edit'])->name('doctor.profile.edit');

    Route::patch('/doctor/profile/update', [DoctorController::class, 'update'])->name('doctor.profile.update');

    Route::put('/doctor/profile/update', [DoctorController::class, 'updatePassword'])->name('doctor.password.update');

    Route::get('/doctor/logout', [DoctorController::class, 'doctorLogout'])->name('doctor.logout');
});

Route::middleware('auth','role:admin')->group(function(){
    Route::get('/admin/dashboard', [UsersController::class, 'AdminDashboard'])->name('admin.dashboard');
});

Route::middleware('auth','role:super_admin')->group(function(){

    Route::get('/super_admin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');

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





