<?php


use App\Http\Controllers\FormUI\GetUiFieldsController;
use App\Http\Controllers\FormUI\UpdateFormUIController;
use App\Http\Controllers\UserPersonInfo\EditUserDoctorDetails;
use App\Http\Controllers\UserPersonInfo\EditUserPersonDetails;
use App\Http\Controllers\UserPersonInfo\UpdateDoctorDetails;
use App\Http\Controllers\UserPersonInfo\UpdateUserPersonDetails;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/ui-fields/{type}', GetUiFieldsController::class)->name('ui-fields')->middleware(['auth', 'verified']);
Route::post('/ui-fields-update/{type}', UpdateFormUIController::class)->name('ui-fields-update')->middleware(['auth', 'verified']);

# Person.

Route::get('dashboard/user/person/edit',EditUserPersonDetails::class)->name('dashboard.user-person-edit')->middleware(['auth', 'verified']);
Route::post('dashboard/user/person/update',UpdateUserPersonDetails::class)->name('dashboard.user-person-update')->middleware(['auth', 'verified']);

Route::get('dashboard/user/doctor/edit', EditUserDoctorDetails::class)->name('dashboard.user-doctor-edit');
Route::post('dashboard/user/doctor/update', UpdateDoctorDetails::class)->name('dashboard.user-doctor-update');


require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
require __DIR__.'/patient_cases.php';
