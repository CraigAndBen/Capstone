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

Route::get('/', [UsersController::class, 'home'])->name('home');

Route::get('/user/dashboard', [UsersController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/user/logout', [UsersController::class, 'userLogout'])->name('user.logout');

// User Appointment
Route::get('/user/show/appointment', [AppointmentController::class, 'showAppointment'])->name('user.show.appointment');
Route::get('/user/appointment/event', [AppointmentController::class, 'appointmentEvents'])->name('user.appointment.event');
Route::get('/user/appointment', [AppointmentController::class, 'appointment'])->name('user.appointment');
Route::get('/user/appointment/confirmed', [AppointmentController::class, 'confirmedAppointmentList'])->name('user.confirmed.appointment');

Route::get('/user/appointment/done', [AppointmentController::class, 'doneAppointmentList'])->name('user.done.appointment');
Route::get('/user/appointment/cancelled', [AppointmentController::class, 'cancelledAppointmentList'])->name('user.cancelled.appointment');
Route::post('/user/create/appointment', [AppointmentController::class, 'createAppointment'])->name('user.create.appointment');
Route::post('/user/update/appointment', [AppointmentController::class, 'updateAppointment'])->name('user.update.appointment');
Route::post('/user/cancel/appointment', [AppointmentController::class, 'cancelAppointment'])->name('user.cancel.appointment');
Route::post('/user/appointment/delete', [AppointmentController::class, 'deleteAppointment'])->name('user.appointment.delete');

// User Notification
Route::get('/user/notification', [UsersController::class, 'notification'])->name('user.notification');
Route::post('/user/notification/read', [UsersController::class, 'notificationRead'])->name('user.notification.read');
Route::post('/user/notification/delete', [UsersController::class, 'notificationDelete'])->name('user.notification.delete');

//profile
Route::get('/user/profile', [ProfileController::class, 'profile'])->name('user.profile');
Route::get('/user/profile/password', [ProfileController::class, 'passwordProfile'])->name('user.profile.password');
Route::post('/user/profile/update', [ProfileController::class, 'profileUpdate'])->name('user.profile.update');
Route::post('/user/profile/update/password', [ProfileController::class, 'updatePassword'])->name('user.password.update');


require __DIR__ . '/auth.php';

Route::middleware(['auth', 'role:nurse'])->group(function () {

    // Dashboard
    Route::get('/nurse/dashboard', [NurseController::class, 'dashboard'])->name('nurse.dashboard');

    // Profile
    Route::get('/nurse/profile', [NurseController::class, 'profile'])->name('nurse.profile');
    Route::get('/nurse/profile/password', [NurseController::class, 'passwordProfile'])->name('nurse.profile.password');
    Route::post('/nurse/profile/update', [NurseController::class, 'profileUpdate'])->name('nurse.profile.update');
    Route::post('/nurse/profile/update/password', [NurseController::class, 'updatePassword'])->name('nurse.password.update');

    // Patient
    Route::get('/nurse/patient', [NurseController::class, 'patientList'])->name('nurse.patient');
    Route::get('/nurse/patient/search', [NurseController::class, 'patientSearch'])->name('nurse.patient.search');

    // Nurse Notification
    Route::get('/nurse/notification', [NurseController::class, 'notification'])->name('nurse.notification');
    Route::post('/nurse/notification/read', [NurseController::class, 'notificationRead'])->name('nurse.notification.read');

    // Logout
    Route::get('/nurse/logout', [NurseController::class, 'nurseLogout'])->name('nurse.logout');

});

// Doctor

Route::middleware(['auth', 'role:doctor'])->group(function () {

    // Dashboard
    Route::get('/doctor/dashboard', [DoctorController::class, 'dashboard'])->name('doctor.dashboard');

    // Profile
    Route::get('/doctor/profile', [DoctorController::class, 'profile'])->name('doctor.profile');
    Route::get('/doctor/profile/social', [DoctorController::class, 'socialProfile'])->name('doctor.social');
    Route::get('/doctor/profile/password', [DoctorController::class, 'passwordProfile'])->name('doctor.profile.password');
    Route::post('/doctor/profile/update', [DoctorController::class, 'profileUpdate'])->name('doctor.profile.update');
    Route::post('/doctor/profile/update/password', [DoctorController::class, 'updatePassword'])->name('doctor.password.update');
    Route::post('/doctor/profile/social/update', [DoctorController::class, 'updateSocialProfile'])->name('doctor.social.update');

    // Appointment
    Route::get('/doctor/appointment', [DoctorController::class, 'appointment'])->name('doctor.appointment');
    Route::get('/doctor/appointment/confirmed', [DoctorController::class, 'confirmedAppointmentList'])->name('doctor.appointment.confirmed');
    Route::get('/doctor/appointment/done', [DoctorController::class, 'doneAppointmentList'])->name('doctor.appointment.done');
    Route::post('/doctor/appointment/confirm', [DoctorController::class, 'confirmedAppointment'])->name('doctor.confirm.appointment');
    Route::post('/doctor/appointment/finish', [DoctorController::class, 'doneAppointment'])->name('doctor.finish.appointment');
    Route::post('/doctor/appointment/search', [DoctorController::class, 'appointmentSearch'])->name('doctor.appointment.search');
    Route::post('/doctor/confirmed/appointment/search', [DoctorController::class, 'confirmedAppointmentSearch'])->name('doctor.confimed.appointment.search');
    Route::post('/doctor/done/appointment/search', [DoctorController::class, 'doneAppointmentSearch'])->name('doctor.done.appointment.search');
    Route::get('/doctor/appointment/calendar', [DoctorController::class, 'appointmentCalendar'])->name('doctor.appointment.calendar');
    Route::get('/doctor/appointment/calendar/events', [DoctorController::class, 'appointmentEvents'])->name('doctor.appointment.calendar.events');
    Route::post('/doctor/appointment/calendar/confirm', [DoctorController::class, 'calendarConfirmedAppointment'])->name('doctor.appointment.calendar.confirm');

    // Patient
    Route::get('/doctor/patient', [DoctorController::class, 'patientList'])->name('doctor.patient');
    Route::get('/doctor/outpatient', [DoctorController::class, 'outpatientList'])->name('doctor.outpatient');
    Route::get('/doctor/admotted/patient', [DoctorController::class, 'admittedPatientList'])->name('doctor.admitted');
    Route::post('/doctor/patient/update', [DoctorController::class, 'patientUpdate'])->name('doctor.patient.update');
    Route::post('/doctor/patient/search', [DoctorController::class, 'patientSearch'])->name('doctor.patient.search');
    Route::post('/doctor/admitted/patient/search', [DoctorController::class, 'admittedPatientSearch'])->name('doctor.admitted.search');
    Route::post('/doctor/outpatient/search', [DoctorController::class, 'outpatientSearch'])->name('doctor.outpatient.search');

    // Doctor Notification
    Route::get('/doctor/notification', [DoctorController::class, 'notification'])->name('doctor.notification');
    Route::post('/doctor/notification/read', [DoctorController::class, 'notificationRead'])->name('doctor.notification.read');

    // Logout
    Route::get('/doctor/logout', [DoctorController::class, 'doctorLogout'])->name('doctor.logout');
});

// Admin

Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Profile
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('/admin/profile/password', [AdminController::class, 'passwordProfile'])->name('admin.profile.password');
    Route::post('/admin/profile/update', [AdminController::class, 'profileUpdate'])->name('admin.profile.update');
    Route::post('/admin/profile/update/password', [AdminController::class, 'updatePassword'])->name('admin.password.update');

    // Logout
    Route::get('/admin/logout', [AdminController::class, 'adminLogout'])->name('admin.logout');

    // Patient
    Route::get('/admin/patient', [AdminController::class, 'patientList'])->name('admin.patient');
    Route::get('/admin/patient/admitted', [AdminController::class, 'patienAdmittedtList'])->name('admin.patient.admitted');
    Route::get('/admin/patient/outpatient', [AdminController::class, 'outpatientList'])->name('admin.patient.outpatient');
    Route::get('/admin/patient/search', [AdminController::class, 'patientSearch'])->name('admin.patient.search');
    Route::get('/admin/patient/admitted/search', [AdminController::class, 'patientAdmittedSearch'])->name('admin.patient.admitted.search');
    Route::get('/admin/patient/outpatient/search', [AdminController::class, 'outpatientSearch'])->name('admin.patient.outpatient.search');
    Route::post('/admin/patient/store', [AdminController::class, 'patientStore'])->name('admin.patient.store');
    Route::post('/admin/outpatient/store', [AdminController::class, 'outpatientStore'])->name('admin.outpatient.store');
    Route::post('/admin/patient/update', [AdminController::class, 'patientUpdate'])->name('admin.patient.update');

    //  Notification
    Route::get('/admin/notification', [AdminController::class, 'notification'])->name('admin.notification');
    Route::post('/admin/notification/read', [AdminController::class, 'notificationRead'])->name('admin.notification.read');

    // Demographics
    // Gender
    Route::get('/admin/demographics/gender', [AdminController::class, 'genderDemo'])->name('admin.demographics.gender');
    Route::get('/admin/demogrpahics/gender/search', [AdminController::class, 'genderSearch'])->name('admin.demographics.gender.search');
    //Age
    Route::get('/admin/demographics/age', [AdminController::class, 'ageDemo'])->name('admin.demographics.age');
    Route::get('/admin/demogrpahics/age/search', [AdminController::class, 'ageSearch'])->name('admin.demographics.age.search');
    //Admit
    Route::get('/admin/demographics/admitted', [AdminController::class, 'admittedDemo'])->name('admin.demographics.admitted');
    Route::get('/admin/demogrpahics/admit/search', [AdminController::class, 'admittedDemoSearch'])->name('admin.demographics.admitted.search');
    //Admit
    Route::get('/admin/demographics/outpatient', [AdminController::class, 'outpatientDemo'])->name('admin.demographics.outpatient');
    Route::get('/admin/demogrpahics/outpatient/search', [AdminController::class, 'outpatientDemoSearch'])->name('admin.demographics.outpatient.search');
    //Diagnose
    Route::get('/admin/demographics/diagnose', [AdminController::class, 'diagnoseDemo'])->name('admin.demographics.diagnose');
    Route::get('/admin/demogrpahics/diagnose/search', [AdminController::class, 'diagnoseSearch'])->name('admin.demographics.diagnose.search');

    //Trend
    //Diagnose Rising Trend
    Route::get('/admin/trend/diagnose', [AdminController::class, 'diagnoseTrend'])->name('admin.trend.diagnose');
    Route::get('/admin/trend/diagnose/search', [AdminController::class, 'diagnoseTrendSearch'])->name('admin.trend.diagnose.search');

    //Report
    Route::post('/admin/gender/report', [AdminController::class, 'genderReport'])->name('admin.gender.report');
    Route::post('/admin/age/report', [AdminController::class, 'ageReport'])->name('admin.age.report');
    Route::post('/admin/admitted/report', [AdminController::class, 'admittedDemoReport'])->name('admin.admitted.report');
    Route::post('/admin/outpatient/report', [AdminController::class, 'outpatientDemoReport'])->name('admin.outpatient.report');
    Route::post('/admin/diagnose/report', [AdminController::class, 'diagnoseReport'])->name('admin.diagnose.report');
    Route::post('/admin/diagnose_trend/report', [AdminController::class, 'diagnoseTrendReport'])->name('admin.diagnose.trend.report');


});

Route::middleware(['auth', 'role:super_admin'])->group(function () {

    Route::get('/super_admin/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');

    // Profile
    Route::get('/superadmin/profile', [SuperAdminController::class, 'profile'])->name('superadmin.profile');
    Route::get('/superadmin/profile/password', [SuperAdminController::class, 'passwordProfile'])->name('superadmin.profile.password');
    Route::post('/superadmin/profile/update', [SuperAdminController::class, 'profileUpdate'])->name('superadmin.profile.update');
    Route::post('/superadmin/profile/update/password', [SuperAdminController::class, 'updatePassword'])->name('superadmin.password.update');

    // Notification
    Route::get('/superadmin/notification', [SuperAdminController::class, 'notification'])->name('superadmin.notification');
    Route::post('/superadmin/notification/read', [SuperAdminController::class, 'notificationRead'])->name('superadmin.notification.read');

    // Appointment
    Route::get('/superadmin/appointment', [SuperAdminController::class, 'appointment'])->name('superadmin.appointment');
    Route::get('/superadmin/appointment/search', [SuperAdminController::class, 'appointmentSearch'])->name('superadmin.appointment.search');

    // Doctor
    Route::get('/super_admin/doctor', [SuperAdminController::class, 'doctor'])->name('superadmin.doctor');
    Route::post('/super_admin/doctor/create', [SuperAdminController::class, 'createDoctor'])->name('superadmin.store.doctor');
    Route::post('/super_admin/doctor/update', [SuperAdminController::class, 'updateDoctorInfo'])->name('superadmin.update.doctor');
    Route::post('/super_admin/doctor/update/password', [SuperAdminController::class, 'updateDoctorPassword'])->name('superadmin.doctor.password.update');

    // Nurse
    Route::get('/super_admin/nurse', [SuperAdminController::class, 'nurse'])->name('superadmin.nurse');
    Route::post('/super_admin/nurse/create', [SuperAdminController::class, 'createNurse'])->name('superadmin.store.nurse');
    Route::post('/super_admin/nurse/update', [SuperAdminController::class, 'updateNurseInfo'])->name('superadmin.update.nurse');
    Route::post('/super_admin/nurse/update/password', [SuperAdminController::class, 'updateNursePassword'])->name('superadmin.nurse.password.update');
    Route::post('/super_admin/nuser/update/status', [SuperAdminController::class, 'updateNurseStatus'])->name('superadmin.nurse.update.status');


    // User
    Route::get('/super_admin/user', [SuperAdminController::class, 'user'])->name('superadmin.user');
    Route::post('/super_admin/user/create', [SuperAdminController::class, 'createUser'])->name('superadmin.store.user');
    Route::post('/super_admin/user/update', [SuperAdminController::class, 'updateUserInfo'])->name('superadmin.update.user');
    Route::post('/super_admin/user/update/password', [SuperAdminController::class, 'updateUserPassword'])->name('superadmin.user.password.update');

    // Admin
    Route::get('/super_admin/admin', [SuperAdminController::class, 'admin'])->name('superadmin.admin');
    Route::post('/super_admin/admin/create', [SuperAdminController::class, 'createAdmin'])->name('superadmin.store.admin');
    Route::post('/super_admin/admin/update', [SuperAdminController::class, 'updateAdminInfo'])->name('superadmin.update.admin');
    Route::post('/super_admin/admin/update/status', [SuperAdminController::class, 'updateAdminStatus'])->name('superadmin.admin.update.status');
    Route::post('/super_admin/admin/update/password', [SuperAdminController::class, 'updateAdminPassword'])->name('superadmin.admin.password.update');

    // Patient
    Route::get('/superadmin/patient', [SuperAdminController::class, 'patientList'])->name('superadmin.patient');
    Route::get('/superadmin/patient/admitted', [SuperAdminController::class, 'patienAdmittedtList'])->name('superadmin.patient.admitted');
    Route::get('/superadmin/patient/outpatient', [SuperAdminController::class, 'outpatientList'])->name('superadmin.patient.outpatient');
    Route::get('/superadmin/patient/search', [SuperAdminController::class, 'patientSearch'])->name('superadmin.patient.search');
    Route::get('/superadmin/patient/admitted/search', [SuperAdminController::class, 'patientAdmittedSearch'])->name('superadmin.patient.admitted.search');
    Route::get('/superadmin/patient/outpatient/search', [SuperAdminController::class, 'outpatientSearch'])->name('superadmin.patient.outpatient.search');
    Route::post('/superadmin/patient/store', [SuperAdminController::class, 'patientStore'])->name('superadmin.patient.store');
    Route::post('/superadmin/outpatient/store', [SuperAdminController::class, 'outpatientStore'])->name('superadmin.outpatient.store');
    Route::post('/superadmin/patient/update', [SuperAdminController::class, 'patientUpdate'])->name('superadmin.patient.update');

    // Demographics
    // Gender
    Route::get('/superadmin/demographics/gender', [SuperAdminController::class, 'genderDemo'])->name('superadmin.demographics.gender');
    Route::get('/superadmin/demogrpahics/gender/search', [SuperAdminController::class, 'genderSearch'])->name('superadmin.demographics.gender.search');
    //Age
    Route::get('/superadmin/demographics/age', [SuperAdminController::class, 'ageDemo'])->name('superadmin.demographics.age');
    Route::get('/superadmin/demogrpahics/age/search', [SuperAdminController::class, 'ageSearch'])->name('superadmin.demographics.age.search');
    //Admit
    Route::get('/superadmin/demographics/admitted', [SuperAdminController::class, 'admittedDemo'])->name('superadmin.demographics.admitted');
    Route::get('/superadmin/demogrpahics/admit/search', [SuperAdminController::class, 'admittedDemoSearch'])->name('superadmin.demographics.admitted.search');
    //Admit
    Route::get('/superadmin/demographics/outpatient', [SuperAdminController::class, 'outpatientDemo'])->name('superadmin.demographics.outpatient');
    Route::get('/superadmin/demogrpahics/outpatient/search', [SuperAdminController::class, 'outpatientDemoSearch'])->name('superadmin.demographics.outpatient.search');
    //Diagnose
    Route::get('/superadmin/demographics/diagnose', [SuperAdminController::class, 'diagnoseDemo'])->name('superadmin.demographics.diagnose');
    Route::get('/superadmin/demogrpahics/diagnose/search', [SuperAdminController::class, 'diagnoseSearch'])->name('superadmin.demographics.diagnose.search');
    //Appointment
    Route::get('/superadmin/demographics/appointment', [SuperAdminController::class, 'appointmentDemo'])->name('superadmin.demographics.appointment');
    Route::get('/superadmin/demogrpahics/appointment/search', [SuperAdminController::class, 'appointmentDemoSearch'])->name('superadmin.demographics.appointment.search');

    //Trend
    //Diagnose Rising Trend
    Route::get('/superadmin/trend/diagnose', [SuperAdminController::class, 'diagnoseTrend'])->name('superadmin.trend.diagnose');
    Route::get('/superadmin/trend/diagnose/search', [SuperAdminController::class, 'diagnoseTrendSearch'])->name('superadmin.trend.diagnose.search');

    //Report
    Route::post('/superadmin/gender/report', [SuperAdminController::class, 'genderReport'])->name('superadmin.gender.report');
    Route::post('/superadmin/age/report', [SuperAdminController::class, 'ageReport'])->name('superadmin.age.report');
    Route::post('/superadmin/admitted/report', [SuperAdminController::class, 'admittedDemoReport'])->name('superadmin.admitted.report');
    Route::post('/superadmin/outpatient/report', [SuperAdminController::class, 'outpatientDemoReport'])->name('superadmin.outpatient.report');
    Route::post('/superadmin/appointment/report', [SuperAdminController::class, 'appointmentReport'])->name('superadmin.appointment.report');
    Route::post('/superadmin/diagnose/report', [SuperAdminController::class, 'diagnoseReport'])->name('superadmin.diagnose.report');
    Route::post('/superadmin/diagnose_trend/report', [SuperAdminController::class, 'diagnoseTrendReport'])->name('superadmin.diagnose.trend.report');

    // Super Admin Logout
    Route::get('/super_admin/logout', [SuperAdminController::class, 'superAdminLogout'])->name('superadmin.logout');
});