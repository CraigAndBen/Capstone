<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\NurseController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SupplyOFficer;
use App\Http\Controllers\SupplyOfficerController;
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
Route::get('/user/appointment/holiday', [AppointmentController::class, 'holidayEvents'])->name('user.appointment.holiday');
Route::post('/user/appointment/doctor/specialties', [AppointmentController::class, 'doctorSpecialties'])->name('user.appointment.doctor.specialties');
Route::post('/user/appointment/doctor/specialties/time', [AppointmentController::class, 'getAvailableTime'])->name('user.appointment.doctor.specialties.time');
Route::get('/user/appointment', [AppointmentController::class, 'appointment'])->name('user.appointment');
Route::get('/user/appointment/confirmed', [AppointmentController::class, 'confirmedAppointmentList'])->name('user.confirmed.appointment');

Route::get('/user/appointment/done', [AppointmentController::class, 'doneAppointmentList'])->name('user.done.appointment');
Route::get('/user/appointment/cancelled', [AppointmentController::class, 'cancelledAppointmentList'])->name('user.cancelled.appointment');
Route::get('/user/appointment/unavailable', [AppointmentController::class, 'unavailableAppointmentList'])->name('user.unavailable.appointment');
Route::post('/user/create/appointment', [AppointmentController::class, 'createAppointment'])->name('user.create.appointment');
Route::post('/user/update/appointment', [AppointmentController::class, 'updateAppointment'])->name('user.update.appointment');
Route::post('/user/cancel/appointment', [AppointmentController::class, 'cancelAppointment'])->name('user.cancel.appointment');
Route::post('/user/appointment/delete', [AppointmentController::class, 'deleteAppointment'])->name('user.appointment.delete');
Route::post('/user/appointment/calendar/cancel', [AppointmentController::class, 'calendarCancelAppointment'])->name('user.appointment.calendar.cancel');


// User Notification
Route::get('/user/notification', [UsersController::class, 'notification'])->name('user.notification');
Route::post('/user/notification/read', [UsersController::class, 'notificationRead'])->name('user.notification.read');
Route::post('/user/notification/delete', [UsersController::class, 'deleteNotification'])->name('user.notification.delete');
Route::post('/user/notification/delete/all', [UsersController::class, 'deleteNotificationAll'])->name('user.notification.delete.all');

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
    Route::post('/nurse/patient/update', [NurseController::class, 'patientUpdate'])->name('nurse.patient.update');


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
    Route::get('/doctor/appointment/calendar/event', [DoctorController::class, 'appointmentEvents'])->name('doctor.appointment.calendar.events');
    Route::get('/doctor/appointment/calendar/holiday', [DoctorController::class, 'holidayEvents'])->name('doctor.appointment.holiday');
    Route::get('/doctor/appointment/calendar/availability/dates', [DoctorController::class, 'availabilityDates'])->name('doctor.appointment.availability.date');
    Route::post('/doctor/appointment/calendar/availability', [DoctorController::class, 'doctorAvailability'])->name('doctor.appointment.availability');
    Route::post('/doctor/appointment/calendar/update/availability', [DoctorController::class, 'updateDoctorAvailability'])->name('doctor.appointment.update.availability');
    Route::post('/doctor/appointment/calendar/confirm', [DoctorController::class, 'calendarConfirmedAppointment'])->name('doctor.appointment.calendar.confirm');
    Route::post('/doctor/appointment/calendar/done', [DoctorController::class, 'calendarDoneAppointment'])->name('doctor.appointment.calendar.done');
    Route::get('/doctor/appointment/report/view', [DoctorController::class, 'viewAppointmentReport'])->name('doctor.appointment.report.view');
    Route::get('/doctor/appointment/report/download', [DoctorController::class, 'downloadAppointmentReport'])->name('doctor.appointment.report.download');

    // Patient
    Route::get('/doctor/patient', [DoctorController::class, 'patientList'])->name('doctor.patient');
    Route::get('/doctor/outpatient', [DoctorController::class, 'outpatientList'])->name('doctor.outpatient');
    Route::get('/doctor/admotted/patient', [DoctorController::class, 'admittedPatientList'])->name('doctor.admitted');
    Route::post('/doctor/patient/update', [DoctorController::class, 'patientUpdate'])->name('doctor.patient.update');
    Route::get('/doctor/patient/{id}/diagnoses', [DoctorController::class, 'getDiagnoses'])->name('doctor.patient.diagnoses');
    Route::get('/doctor/patient/{id}/medications', [DoctorController::class, 'getMedications'])->name('doctor.patient.medications');

    // Doctor Notification
    Route::get('/doctor/notification', [DoctorController::class, 'notification'])->name('doctor.notification');
    Route::post('/doctor/notification/read', [DoctorController::class, 'notificationRead'])->name('doctor.notification.read');
    Route::post('/doctor/notification/delete', [DoctorController::class, 'deleteNotification'])->name('doctor.notification.delete');
    Route::post('/doctor/notification/delete/all', [DoctorController::class, 'deleteNotificationAll'])->name('doctor.notification.delete.all');

    // Logout
    Route::get('/doctor/logout', [DoctorController::class, 'doctorLogout'])->name('doctor.logout');
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/generate/pdf', [AdminController::class, 'generatePdf'])->name('admin.generate.pdf');

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
    Route::post('/admin/patient/store', [AdminController::class, 'patientStore'])->name('admin.patient.store');
    Route::post('/admin/patient/update', [AdminController::class, 'patientUpdate'])->name('admin.patient.update');
    Route::get('/admin/patient/report/download', [AdminController::class, 'downloadPatientReport'])->name('admin.patient.report.download');
    Route::get('/admin/patient/{id}/diagnoses', [AdminController::class, 'getDiagnoses'])->name('admin.patient.diagnoses');
    Route::get('/admin/patient/{id}/medications', [AdminController::class, 'getMedications'])->name('admin.patient.medications');

    //  Notification
    Route::get('/admin/notification', [AdminController::class, 'notification'])->name('admin.notification');
    Route::post('/admin/notification/read', [AdminController::class, 'notificationRead'])->name('admin.notification.read');
    Route::post('/admin/notification/delete', [AdminController::class, 'deleteNotification'])->name('admin.notification.delete');
    Route::post('/admin/notification/delete/all', [AdminController::class, 'deleteNotificationAll'])->name('admin.notification.delete.all');

    // Analytics
    // Gender
    Route::get('/admin/analytics/patient/gender', [AdminController::class, 'patientGenderDemo'])->name('admin.analytics.patient.gender');
    Route::get('/admin/analytics/admitted/gender', [AdminController::class, 'admittedGenderDemo'])->name('admin.analytics.admitted.gender');
    Route::get('/admin/analytics/outpatient/gender', [AdminController::class, 'outpatientGenderDemo'])->name('admin.analytics.outpatient.gender');
    Route::get('/admin/analytics/patient/gender/search', [AdminController::class, 'patientGenderSearch'])->name('admin.analytics.patient.gender.search');

    //Age
    Route::get('/admin/analytics/patient/age', [AdminController::class, 'patientAgeDemo'])->name('admin.analytics.patient.age');
    Route::get('/admin/analytics/admitted/age', [AdminController::class, 'admittedAgeDemo'])->name('admin.analytics.admitted.age');
    Route::get('/admin/analytics/outpatient/age', [AdminController::class, 'outpatientAgeDemo'])->name('admin.analytics.outpatient.age');
    Route::get('/admin/analytics/patient/age/search', [AdminController::class, 'patientAgeSearch'])->name('admin.analytics.patient.age.search');

    //Admitted
    Route::get('/admin/analytics/admitted', [AdminController::class, 'admittedDemo'])->name('admin.analytics.admitted');
    Route::get('/admin/analytics/admit/search', [AdminController::class, 'admittedDemoSearch'])->name('admin.analytics.admitted.search');

    //Outpatient
    Route::get('/admin/analytics/outpatient', [AdminController::class, 'outpatientDemo'])->name('admin.analytics.outpatient');
    Route::get('/admin/analytics/outpatient/search', [AdminController::class, 'outpatientDemoSearch'])->name('admin.analytics.outpatient.search');

    //Diagnose
    Route::get('/admin/analytics/patient/diagnose', [AdminController::class, 'patientDiagnoseDemo'])->name('admin.analytics.patient.diagnose');
    Route::get('/admin/analytics/admitted/diagnose', [AdminController::class, 'admittedDiagnoseDemo'])->name('admin.analytics.admitted.diagnose');
    Route::get('/admin/analytics/outpatient/diagnose', [AdminController::class, 'outpatientDiagnoseDemo'])->name('admin.analytics.outpatient.diagnose');
    Route::get('/admin/analytics/diagnose/search', [AdminController::class, 'diagnoseSearch'])->name('admin.analytics.diagnose.search');

    //Trend

    //Diagnose Trend
    Route::get('/admin/analytics/diagnose_trend/patient', [AdminController::class, 'patientDiagnoseTrend'])->name('admin.analytics.patient.diagnose_trend');
    Route::get('/admin/analytics/diagnose_trend/admitted', [AdminController::class, 'admittedDiagnoseTrend'])->name('admin.analytics.admitted.diagnose_trend');
    Route::get('/admin/analytics/diagnose_trend/outpatient', [AdminController::class, 'outpatientDiagnoseTrend'])->name('admin.analytics.outpatient.diagnose_trend');
    Route::get('/admin/analytics/diagnose_trend/diagnose/search', [AdminController::class, 'diagnoseTrendSearch'])->name('admin.analytics.trend.diagnose.search');

    //Report
    Route::post('/admin/gender/report', [AdminController::class, 'genderReport'])->name('admin.gender.report');
    Route::post('/admin/gender/report/save', [AdminController::class, 'genderReportSave'])->name('admin.gender.report.save');
    Route::post('/admin/age/report', [AdminController::class, 'ageReport'])->name('admin.age.report');
    Route::post('/admin/age/report/save', [AdminController::class, 'ageReportSave'])->name('admin.age.report.save');
    Route::post('/admin/admitted/report', [AdminController::class, 'admittedDemoReport'])->name('admin.admitted.report');
    Route::post('/admin/admitted/report/save', [AdminController::class, 'admittedDemoReportSave'])->name('admin.admitted.report.save');
    Route::post('/admin/outpatient/report', [AdminController::class, 'outpatientDemoReport'])->name('admin.outpatient.report');
    Route::post('/admin/outpatient/report/save', [AdminController::class, 'outpatientDemoReportSave'])->name('admin.outpatient.report.save');
    Route::post('/admin/diagnose/report', [AdminController::class, 'diagnoseReport'])->name('admin.diagnose.report');
    Route::post('/admin/diagnose/report/save', [AdminController::class, 'diagnoseReportSave'])->name('admin.diagnose.report.save');
    Route::post('/admin/diagnose_trend/report', [AdminController::class, 'diagnoseTrendReport'])->name('admin.diagnose.trend.report');
    Route::post('/admin/diagnose_trend/report/save', [AdminController::class, 'diagnoseTrendReportSave'])->name('admin.diagnose.trend.report.save');


});

// Super Admin
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
    Route::post('/superadmin/notification/delete', [SuperAdminController::class, 'deleteNotification'])->name('superadmin.notification.delete');
    Route::post('/superadmin/notification/delete/all', [SuperAdminController::class, 'deleteNotificationAll'])->name('superadmin.notification.delete.all');

    // Appointment
    Route::get('/superadmin/appointment', [SuperAdminController::class, 'appointment'])->name('superadmin.appointment');

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

    // Supply Officer
    Route::get('/super_admin/supply_officer', [SuperAdminController::class, 'supplyOfficer'])->name('superadmin.supply_officer');
    Route::post('/super_admin/supply_officer/create', [SuperAdminController::class, 'createSupplyOfficer'])->name('superadmin.store.supply_officer');
    Route::post('/super_admin/supply_officer/update', [SuperAdminController::class, 'updateSupplyOfficerInfo'])->name('superadmin.update.supply_officer');
    Route::post('/super_admin/supply_officer/update/password', [SuperAdminController::class, 'updateSupplyOfficerPassword'])->name('superadmin.supply_officer.password.update');

    // Staff
    Route::get('/super_admin/staff', [SuperAdminController::class, 'staff'])->name('superadmin.staff');
    Route::post('/super_admin/staff/create', [SuperAdminController::class, 'createStaff'])->name('superadmin.store.staff');
    Route::post('/super_admin/staff/update', [SuperAdminController::class, 'updateStaffInfo'])->name('superadmin.update.staff');
    Route::post('/super_admin/staff/update/password', [SuperAdminController::class, 'updateStaffPassword'])->name('superadmin.staff.password.update');

    // Cashier
    Route::get('/super_admin/cashier', [SuperAdminController::class, 'cashier'])->name('superadmin.cashier');
    Route::post('/super_admin/cashier/create', [SuperAdminController::class, 'createCashier'])->name('superadmin.store.cashier');
    Route::post('/super_admin/cashier/update', [SuperAdminController::class, 'updateCashierInfo'])->name('superadmin.update.cashier');
    Route::post('/super_admin/cashier/update/password', [SuperAdminController::class, 'updateCashierPassword'])->name('superadmin.cashier.password.update');

    // Pharmacist
    Route::get('/super_admin/pharmacist', [SuperAdminController::class, 'pharmacist'])->name('superadmin.pharmacist');
    Route::post('/super_admin/pharmacist/create', [SuperAdminController::class, 'createPharmacist'])->name('superadmin.store.pharmacist');
    Route::post('/super_admin/pharmacist/update', [SuperAdminController::class, 'updatePharmacistInfo'])->name('superadmin.update.pharmacist');
    Route::post('/super_admin/pharmacist/update/password', [SuperAdminController::class, 'updatePharmacistPassword'])->name('superadmin.pharmacist.password.update');

    // Analytics
    // Gender
    Route::get('/super_admin/analytics/patient/gender', [SuperAdminController::class, 'patientGenderDemo'])->name('superadmin.analytics.patient.gender');
    Route::get('/super_admin/analytics/admitted/gender', [SuperAdminController::class, 'admittedGenderDemo'])->name('superadmin.analytics.admitted.gender');
    Route::get('/super_admin/analytics/outpatient/gender', [SuperAdminController::class, 'outpatientGenderDemo'])->name('superadmin.analytics.outpatient.gender');
    Route::get('/super_admin/analytics/patient/gender/search', [SuperAdminController::class, 'patientGenderSearch'])->name('superadmin.analytics.patient.gender.search');

    //Age
    Route::get('/super_admin/analytics/patient/age', [SuperAdminController::class, 'patientAgeDemo'])->name('superadmin.analytics.patient.age');
    Route::get('/super_admin/analytics/admitted/age', [SuperAdminController::class, 'admittedAgeDemo'])->name('superadmin.analytics.admitted.age');
    Route::get('/super_admin/analytics/outpatient/age', [SuperAdminController::class, 'outpatientAgeDemo'])->name('superadmin.analytics.outpatient.age');
    Route::get('/super_admin/analytics/patient/age/search', [SuperAdminController::class, 'patientAgeSearch'])->name('superadmin.analytics.patient.age.search');

    //Admitted
    Route::get('/super_admin/analytics/admitted', [SuperAdminController::class, 'admittedDemo'])->name('superadmin.analytics.admitted');
    Route::get('/super_admin/analytics/admit/search', [SuperAdminController::class, 'admittedDemoSearch'])->name('superadmin.analytics.admitted.search');

    //Outpatient
    Route::get('/super_admin/analytics/outpatient', [SuperAdminController::class, 'outpatientDemo'])->name('superadmin.analytics.outpatient');
    Route::get('/super_admin/analytics/outpatient/search', [SuperAdminController::class, 'outpatientDemoSearch'])->name('superadmin.analytics.outpatient.search');

    //Diagnose
    Route::get('/super_admin/analytics/patient/diagnose', [SuperAdminController::class, 'patientDiagnoseDemo'])->name('superadmin.analytics.patient.diagnose');
    Route::get('/super_admin/analytics/admitted/diagnose', [SuperAdminController::class, 'admittedDiagnoseDemo'])->name('superadmin.analytics.admitted.diagnose');
    Route::get('/super_admin/analytics/outpatient/diagnose', [SuperAdminController::class, 'outpatientDiagnoseDemo'])->name('superadmin.analytics.outpatient.diagnose');
    Route::get('/super_admin/analytics/diagnose/search', [SuperAdminController::class, 'diagnoseSearch'])->name('superadmin.analytics.diagnose.search');

    //Trend

    //Diagnose Trend
    Route::get('/super_admin/analytics/diagnose_trend/patient', [SuperAdminController::class, 'patientDiagnoseTrend'])->name('superadmin.analytics.patient.diagnose_trend');
    Route::get('/super_admin/analytics/diagnose_trend/admitted', [SuperAdminController::class, 'admittedDiagnoseTrend'])->name('superadmin.analytics.admitted.diagnose_trend');
    Route::get('/super_admin/analytics/diagnose_trend/outpatient', [SuperAdminController::class, 'outpatientDiagnoseTrend'])->name('superadmin.analytics.outpatient.diagnose_trend');
    Route::get('/super_admin/analytics/diagnose_trend/diagnose/search', [SuperAdminController::class, 'diagnoseTrendSearch'])->name('superadmin.analytics.trend.diagnose.search');

    //Report
    Route::post('/super_admin/gender/report', [SuperAdminController::class, 'genderReport'])->name('superadmin.gender.report');
    Route::post('/super_admin/gender/report/save', [SuperAdminController::class, 'genderReportSave'])->name('superadmin.gender.report.save');
    Route::post('/super_admin/age/report', [SuperAdminController::class, 'ageReport'])->name('superadmin.age.report');
    Route::post('/super_admin/age/report/save', [SuperAdminController::class, 'ageReportSave'])->name('superadmin.age.report.save');
    Route::post('/super_admin/admitted/report', [SuperAdminController::class, 'admittedDemoReport'])->name('superadmin.admitted.report');
    Route::post('/super_admin/admitted/report/save', [SuperAdminController::class, 'admittedDemoReportSave'])->name('superadmin.admitted.report.save');
    Route::post('/super_admin/outpatient/report', [SuperAdminController::class, 'outpatientDemoReport'])->name('superadmin.outpatient.report');
    Route::post('/super_admin/outpatient/report/save', [SuperAdminController::class, 'outpatientDemoReportSave'])->name('superadmin.outpatient.report.save');
    Route::post('/super_admin/diagnose/report', [SuperAdminController::class, 'diagnoseReport'])->name('superadmin.diagnose.report');
    Route::post('/super_admin/diagnose/report/save', [SuperAdminController::class, 'diagnoseReportSave'])->name('superadmin.diagnose.report.save');
    Route::post('/super_admin/diagnose_trend/report', [SuperAdminController::class, 'diagnoseTrendReport'])->name('superadmin.diagnose.trend.report');
    Route::post('/super_admin/diagnose_trend/report/save', [SuperAdminController::class, 'diagnoseTrendReportSave'])->name('superadmin.diagnose.trend.report.save');

    // Report History
    Route::get('/superadmin/report/history', [SuperAdminController::class, 'reportHistory'])->name('superadmin.report.history');
    Route::get('/superadmin/report/history/search', [SuperAdminController::class, 'reportHistorySearch'])->name('superadmin.report.history.search');

    // Delete User
    Route::post('/superadmin/account/delete', [SuperAdminController::class, 'deleteUser'])->name('superadmin.delete');

    // Inventory
    Route::get('/superadmin/product', [SuperAdminController::class, 'productList'])->name('superadmin.product');
    Route::post('/superadmin/product/create', [SuperAdminController::class, 'productStore'])->name('superadmin.product.create');
    Route::get('/superadmin/product_details/{id}', [SuperAdminController::class, 'productdetail'])->name('superadmin.product.details');
    Route::post('/superadmin/product/{id}', [SuperAdminController::class, 'Productupdate'])->name('superadmin.product.update');
    Route::get('/superadmin/product/delete/{id}', [SuperAdminController::class, 'productdelete'])->name('superadmin.product.delete');
    Route::get('/superadmin/category', [SuperAdminController::class, 'categoryList'])->name('superadmin.category');
    Route::post('/superadmin/category/create', [SuperAdminController::class, 'categoryStore'])->name('superadmin.category.create');
    Route::post('/superadmin/category/{id}', [SuperAdminController::class, 'categoryupdate'])->name('superadmin.category.update');
    Route::get('/superadmin/category{id}', [SuperAdminController::class, 'categorydelete'])->name('superadmin.category.delete');

    //Expiration
    Route::get('/superadmin/product/expiring_soon', [SuperAdminController::class, 'expirationproduct'])->name('superadmin.product.expiration');

    // Inventory Demo
    Route::get('/superadmin/inventory_demo/inventorydemo', [SuperAdminController::class, 'inventoryDemo'])->name('superadmin.inventory.demo');
    Route::get('/superadmin/inventory_demo/inventorydemo_search', [SuperAdminController::class, 'inventorydemoSearch'])->name('superadmin.inventory.demo.search');

    //Request Demo
    Route::get('/superadmin/inventory_demo/requestdemo', [SuperAdminController::class, 'requestDemo'])->name('superadmin.request.demo');
    Route::get('/superadmin/inventory_demo/requestdemo_search', [SuperAdminController::class, 'requestdemoSearch'])->name('superadmin.request.demo.search');

    //Sale Demo
    Route::get('/superadmin/inventory_demo/saledemo', [SuperAdminController::class, 'saleDemo'])->name('superadmin.sale.demo');
    Route::get('/superadmin/inventory_demo/saledemo_search', [SuperAdminController::class, 'saledemoSearch'])->name('superadmin.sale.demo.search');

    //Medicine Demo
    Route::get('/superadmin/inventory_demo/medicinedemo', [SuperAdminController::class, 'medicineDemo'])->name('superadmin.medicine.demo');

    //Product Demo
    Route::get('/superadmin/inventory_demo/productdemo', [SuperAdminController::class, 'productDemo'])->name('superadmin.product.demo');

     //Medication Demo
     Route::get('/superadmin/inventory_demo/medication', [SuperAdminController::class, 'medicationDemo'])->name('superadmin.medication.demo');
     Route::get('/superadmin/inventory_demo/medication/search', [SuperAdminController::class, 'medicationDemoSearch'])->name('superadmin.medication.demo.search');
    
    // Request
    Route::get('/superadmin/request', [SuperAdminController::class, 'requestlist'])->name('superadmin.request');

    // Report
    Route::get('/superadmin/inventorydemo/report', [SuperAdminController::class, 'inventoryReport'])->name('superadmin.inventory.report');
    Route::post('/superadmin/inventorydemo/report/save', [SuperAdminController::class, 'inventoryReportSave'])->name('superadmin.inventory.report.save');
    Route::post('/superadmin/saledemo/report', [SuperAdminController::class, 'saleReport'])->name('superadmin.sale.report');
    Route::post('/superadmin/saledemo/report/save', [SuperAdminController::class, 'saleReportSave'])->name('superadmin.sale.report.save');
    Route::get('/superadmin/requestdemo/report', [SuperAdminController::class, 'requestReport'])->name('superadmin.request.report');
    Route::post('/superadmin/requestdemo/report/save', [SuperAdminController::class, 'requestReportSave'])->name('superadmin.request.report.save');
    Route::get('/superadmin/medicines_report/report', [SuperAdminController::class, 'medicineReport'])->name('superadmin.medicines.report');
    Route::post('/superadmin/medicines_report/report/save', [SuperAdminController::class, 'medicineReportSave'])->name('superadmin.medicines.report.save');
    Route::get('/superadmin/products_report/report', [SuperAdminController::class, 'productsReport'])->name('superadmin.products.report');
    Route::post('/superadmin/products_report/report/save', [SuperAdminController::class, 'productsReportSave'])->name('superadmin.products.report.save');
    Route::get('/superadmin/medication/report', [SuperAdminController::class, 'medicationReport'])->name('superadmin.medication.report');
    Route::post('/superadmin/medication/report/save', [SuperAdminController::class, 'medicationReportSave'])->name('superadmin.medication.report.save');
    Route::get('/superadmin/product/report', [SuperAdminController::class, 'productReport'])->name('superadmin.product.report');
    Route::get('/superadmin/product/list/report', [SuperAdminController::class, 'requestListReport'])->name('superadmin.request.list.report');
    Route::get('/superadmin/expiry/report/view', [SuperAdminController::class, 'viewExpiryReport'])->name('superadmin.product.expiry.report.view');
    Route::get('/superadmin/expiry/report/download', [SuperAdminController::class, 'downloadExpiryReport'])->name('superadmin.product.expiry.report.download');
    Route::get('superadmin/product/expiry/report/download', [SuperAdminController::class, 'downloadExpiryReport'])
    ->name('superadmin.product.expiry.report.download');


    // Super Admin Logout
    Route::get('/super_admin/logout', [SuperAdminController::class, 'superAdminLogout'])->name('superadmin.logout');
});

//Supply Officer
Route::middleware(['auth', 'role:supply_officer'])->group(function () {

    // Dashboard
    Route::get('/supply_officer/dashboard', [SupplyOfficerController::class, 'dashboard'])->name('supply_officer.dashboard');

    // Profile
    Route::get('/supply_officer/profile', [SupplyOfficerController::class, 'profile'])->name('supply_officer.profile');
    Route::get('/supply_officer/profile/password', [SupplyOfficerController::class, 'passwordProfile'])->name('supply_officer.profile.password');
    Route::post('/supply_officer/profile/update', [SupplyOfficerController::class, 'profileUpdate'])->name('supply_officer.profile.update');
    Route::post('/supply_officer/profile/update/password', [SupplyOfficerController::class, 'updatePassword'])->name('supply_officer.password.update');

    // Notification
    Route::get('/supply_officer/notification', [SupplyOfficerController::class, 'notification'])->name('supply_officer.notification');
    Route::post('/supply_officer/notification/read', [SupplyOfficerController::class, 'notificationRead'])->name('supply_officer.notification.read');
    Route::post('/supply_officer/notification/delete', [SupplyOfficerController::class, 'deleteNotification'])->name('supply_officer.notification.delete');
    Route::post('/supply_officer/notification/delete/all', [SupplyOfficerController::class, 'deleteNotificationAll'])->name('supply_officer.notification.delete.all');

    // Inventory
    Route::get('/supply_officer/product', [SupplyOfficerController::class, 'productList'])->name('supply_officer.product');
    Route::post('/supply_officer/product/create', [SupplyOfficerController::class, 'productStore'])->name('supply_officer.product.create');
    Route::get('/supply_officer/product_details/{id}', [SupplyOfficerController::class, 'productdetail'])->name('supply_officer.product.details');
    Route::post('/supply_officer/product/{id}', [SupplyOfficerController::class, 'Productupdate'])->name('supply_officer.product.update');
    Route::get('/supply_officer/product/delete/{id}', [SupplyOfficerController::class, 'productdelete'])->name('supply_officer.product.delete');
    Route::get('/supply_officer/category', [SupplyOfficerController::class, 'categoryList'])->name('supply_officer.category');
    Route::post('/supply_officer/category/create', [SupplyOfficerController::class, 'categoryStore'])->name('supply_officer.category.create');
    Route::post('/supply_officer/category/{id}', [SupplyOfficerController::class, 'categoryupdate'])->name('supply_officer.category.update');
    Route::get('/supply_officer/category{id}', [SupplyOfficerController::class, 'categorydelete'])->name('supply_officer.category.delete');

    //Expiration
    Route::get('/supply_officer/product/expiring_soon', [SupplyOfficerController::class, 'expirationproduct'])->name('supply_officer.product.expiration');


    // Inventory Demo
    Route::get('/supply_officer/inventory_demo/inventorydemo', [SupplyOfficerController::class, 'inventoryDemo'])->name('supply_officer.inventory.demo');
    Route::get('/supply_officer/inventory_demo/inventorydemo_search', [SupplyOfficerController::class, 'inventorydemoSearch'])->name('supply_officer.inventory.demo.search');

    //Request Demo
    Route::get('/supply_officer/inventory_demo/requestdemo', [SupplyOfficerController::class, 'requestDemo'])->name('supply_officer.request.demo');
    Route::get('/supply_officer/inventory_demo/requestdemo_search', [SupplyOfficerController::class, 'requestdemoSearch'])->name('supply_officer.request.demo.search');

    //Sale Demo
    Route::get('/supply_officer/inventory_demo/saledemo', [SupplyOfficerController::class, 'saleDemo'])->name('supply_officer.sale.demo');
    Route::get('/supply_officer/inventory_demo/saledemo_search', [SupplyOfficerController::class, 'saledemoSearch'])->name('supply_officer.sale.demo.search');

    //Medicine Demo
    Route::get('/supply_officer/inventory_demo/medicinedemo', [SupplyOfficerController::class, 'medicineDemo'])->name('supply_officer.medicine.demo');

    //Product Demo
    Route::get('/supply_officer/inventory_demo/productdemo', [SupplyOfficerController::class, 'productDemo'])->name('supply_officer.product.demo');

    //Medication Demo
    Route::get('/supply_officer/inventory_demo/medication', [SupplyOfficerController::class, 'medicationDemo'])->name('supply_officer.medication.demo');
    Route::get('/supply_officer/inventory_demo/medication/search', [SupplyOfficerController::class, 'medicationDemoSearch'])->name('supply_officer.medication.demo.search');
    
    // Request
    Route::get('/supply_officer/request', [SupplyOfficerController::class, 'requestlist'])->name('supply_officer.request');

    // Report
    Route::get('/supply_officer/inventorydemo/report', [SupplyOfficerController::class, 'inventoryReport'])->name('supply_officer.inventory.report');
    Route::post('/supply_officer/inventorydemo/report/save', [SupplyOfficerController::class, 'inventoryReportSave'])->name('supply_officer.inventory.report.save');
    Route::post('/supply_officer/saledemo/report', [SupplyOfficerController::class, 'saleReport'])->name('supply_officer.sale.report');
    Route::post('/supply_officer/saledemo/report/save', [SupplyOfficerController::class, 'saleReportSave'])->name('supply_officer.sale.report.save');
    Route::get('/supply_officer/requestdemo/report', [SupplyOfficerController::class, 'requestReport'])->name('supply_officer.request.report');
    Route::post('/supply_officer/requestdemo/report/save', [SupplyOfficerController::class, 'requestReportSave'])->name('supply_officer.request.report.save');
    Route::get('/supply_officer/product/report/view', [SupplyOfficerController::class, 'viewProductReport'])->name('supply_officer.product.report.view');
    Route::get('/supply_officer/product/report/download', [SupplyOfficerController::class, 'downloadProductReport'])->name('supply_officer.product.report.download');
    Route::get('/supply_officer/category/report/view', [SupplyOfficerController::class, 'viewCategoryReport'])->name('supply_officer.category.report.view');
    Route::get('/supply_officer/category/report/download', [SupplyOfficerController::class, 'downloadCategoryReport'])->name('supply_officer.category.report.download');
    Route::get('/supply_officer/request/list/report/view', [SupplyOfficerController::class, 'viewRequestListReport'])->name('supply_officer.request.list.report.view');
    Route::get('/supply_officer/request/list/report/download', [SupplyOfficerController::class, 'downloadRequestListReport'])->name('supply_officer.request.list.report.download');
    Route::get('/supply_officer/medicines_report/report', [SupplyOfficerController::class, 'medicineReport'])->name('supply_officer.medicines.report');
    Route::post('/supply_officer/medicines_report/report/save', [SupplyOfficerController::class, 'medicineReportSave'])->name('supply_officer.medicines.report.save');
    Route::get('/supply_officer/products_report/report', [SupplyOfficerController::class, 'productsReport'])->name('supply_officer.products.report');
    Route::post('/supply_officer/products_report/report/save', [SupplyOfficerController::class, 'productsReportSave'])->name('supply_officer.products.report.save');
    Route::get('/supply_officer/medication/report', [SupplyOfficerController::class, 'medicationReport'])->name('supply_officer.medication.report');
    Route::post('/supply_officer/medication/report/save', [SupplyOfficerController::class, 'medicationReportSave'])->name('supply_officer.medication.report.save');
    Route::get('/supply_officer/expiry/report/view', [SupplyOfficerController::class, 'viewExpiryReport'])->name('supply_officer.product.expiry.report.view');
    Route::get('/supply_officer/expiry/report/download', [SupplyOfficerController::class, 'downloadExpiryReport'])->name('supply_officer.product.expiry.report.download');

    // Logout
    Route::get('/supply_officer/logout', [SupplyOfficerController::class, 'supplyOfficerLogout'])->name('supply_officer.logout');

});

// Staff
Route::middleware(['auth', 'role:staff'])->group(function () {

    // Dashboard
    Route::get('/staff/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');

    // Profile
    Route::get('/staff/profile', [StaffController::class, 'profile'])->name('staff.profile');
    Route::get('/staff/profile/password', [StaffController::class, 'passwordProfile'])->name('staff.profile.password');
    Route::post('/staff/profile/update', [StaffController::class, 'profileUpdate'])->name('staff.profile.update');
    Route::post('/staff/profile/update/password', [StaffController::class, 'updatePassword'])->name('staff.password.update');

    //Request
    Route::get('/staff/product', [StaffController::class, 'product'])->name('staff.product');
    Route::get('/staff/request_form', [StaffController::class, 'requestformindex'])->name('staff.request_form');
    Route::post('/staff/request', [StaffController::class, 'requeststore'])->name('staff.request');

    // Logout
    Route::get('/staff/logout', [StaffController::class, 'staffOfficerLogout'])->name('staff.logout');

});

// Pharmacist
Route::middleware(['auth', 'role:pharmacist'])->group(function () {

    // Dashboard
    Route::get('/pharmacist/dashboard', [PharmacistController::class, 'dashboard'])->name('pharmacist.dashboard');

    // Profile
    Route::get('/pharmacist/profile', [PharmacistController::class, 'profile'])->name('pharmacist.profile');
    Route::get('/pharmacist/profile/password', [PharmacistController::class, 'passwordProfile'])->name('pharmacist.profile.password');
    Route::post('/pharmacist/profile/update', [PharmacistController::class, 'profileUpdate'])->name('pharmacist.profile.update');
    Route::post('/pharmacist/profile/update/password', [PharmacistController::class, 'updatePassword'])->name('pharmacist.password.update');


    //Pharmacist
    Route::get('/pharmacist/product', [PharmacistController::class, 'product'])->name('pharmacist.product');

    //Medicine Inventory
    Route::get('/pharmacist/product/inventory_medicine', [PharmacistController::class, 'medicine'])->name('pharmacist.inventory');
    Route::post('/pharmacist/product/createMedicine', [PharmacistController::class, 'medicineStore'])->name('pharmacist.medicine.create');
    Route::get('/pharmacist/medicine_details/{id}', [PharmacistController::class, 'medicineDetail'])->name('pharmacist.medicine.details');
    Route::post('/pharmacist/product/inventory_medicine/{id}', [PharmacistController::class, 'medicineUpdate'])->name('pharmacist.medicine.update');
    Route::get('/pharmacist/inventory_medicine/delete/{id}', [PharmacistController::class, 'medicineDelete'])->name('pharmacist.medicine.delete');

    //Price
    Route::post('/pharmacist/product/create', [PharmacistController::class, 'productCreate'])->name('pharmacist.product.create');
    Route::post('/pharmacist/product/update', [PharmacistController::class, 'productUpdate'])->name('pharmacist.product.update');
    Route::delete('/pharmacist/product/delete/{id}', [PharmacistController::class, 'productDelete'])->name('pharmacist.product.delete');

    // report
    Route::get('/pharmacist/product/report/view', [PharmacistController::class, 'viewProductReport'])->name('pharmacist.product.report.view');
    Route::get('/pharmacist/product/report/download', [PharmacistController::class, 'downloadProductReport'])->name('pharmacist.product.report.download');
    Route::get('/pharmacist/product/medicine_report/view', [PharmacistController::class, 'viewMedicineReport'])->name('pharmacist.medicine.report.view');
    Route::get('/pharmacist/product/medicine_report/download', [PharmacistController::class, 'downloadMedicineReport'])->name('pharmacist.medicine.report.download');



    // Logout
    Route::get('/pharmacist/logout', [PharmacistController::class, 'pharmacistLogout'])->name('pharmacist.logout');

});

// Cashier
Route::middleware(['auth', 'role:cashier'])->group(function () {

    // Dashboard
    Route::get('/cashier/dashboard', [CashierController::class, 'dashboard'])->name('cashier.dashboard');

    // Profile
    Route::get('/cashier/profile', [CashierController::class, 'profile'])->name('cashier.profile');
    Route::get('/cashier/profile/password', [CashierController::class, 'passwordProfile'])->name('cashier.profile.password');
    Route::post('/cashier/profile/update', [CashierController::class, 'profileUpdate'])->name('cashier.profile.update');
    Route::post('/cashier/profile/update/password', [CashierController::class, 'updatePassword'])->name('cashier.password.update');

    // Notification
    Route::get('/cashier/notification', [CashierController::class, 'notification'])->name('cashier.notification');
    Route::post('/cashier/notification/read', [CashierController::class, 'notificationRead'])->name('cashier.notification.read');

    // Purchase
    Route::get('/cashier/product/purchase.list', [CashierController::class, 'purchaseList'])->name('cashier.product.purchase.list');
    Route::get('/cashier/product/purchase', [CashierController::class, 'purchase'])->name('cashier.product.purchase');
    Route::post('/cashier/product/purchase/add', [CashierController::class, 'purchaseAdd'])->name('cashier.product.purchase.add');
    Route::post('/cashier/product/purchase/confirm', [CashierController::class, 'purchaseConfirm'])->name('cashier.product.purchase.confirm');
    Route::delete('/cashier/product/purchase/remove/{key}', [CashierController::class, 'removeProduct'])->name('cashier.product.purchase.remove');
    Route::post('/cashier/product/purchase/receipt/preview', [CashierController::class, 'receiptPreview'])->name('cashier.product.purchase.receipt.preview');
    Route::post('/cashier/product/purchase/receipt', [CashierController::class, 'receipt'])->name('cashier.product.purchase.receipt');





    // Report
    Route::get('/cashier/purchase/report/view', [CashierController::class, 'viewPurchaseReport'])->name('cashier.purchase.report.view');
    Route::get('/cashier/purchase/report/download', [CashierController::class, 'downloadPurchaseReport'])->name('cashier.purchase.report.download');


    // Logout
    Route::get('/cashier/logout', [CashierController::class, 'cashierOfficerLogout'])->name('cashier.logout');

});