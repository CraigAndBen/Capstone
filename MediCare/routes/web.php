<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ProfileController;

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

    Route::get('/super_admin/dashboard', [UsersController::class, 'SuperAdminDashboard'])->name('superadmin.dashboard');

    Route::get('/super_admin/profile', [DoctorController::class, 'edit'])->name('superadmin.profile.edit');

    Route::patch('/super_admin/profile', [DoctorController::class, 'update'])->name('superadmin.profile.update');

    Route::get('/super_admin/logout', [DoctorController::class, 'doctorLogout'])->name('superadmin.logout');
});





