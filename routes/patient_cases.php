<?php

use App\Http\Controllers\MyCases\CreateCase;
use App\Http\Controllers\MyCases\EditCase;
use App\Http\Controllers\MyCases\MyCases;
use App\Http\Controllers\MyCases\StoreMyCase;
use App\Http\Controllers\MyCases\UpdateMyCase;
use App\Http\Controllers\PatientCase\ListAllPatientCases;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard/all-system-patient-cases', ListAllPatientCases::class)->name('all-patient-cases');

Route::get('/dashboard/my-cases', MyCases::class)->name('my-cases');
Route::get('/dashboard/my-cases/create', CreateCase::class)->name('my-cases-create');
Route::post('/dashboard/my-cases/store', StoreMyCase::class)->name('my-cases-store');
Route::get('/dashboard/my-cases/{case}/edit', EditCase::class)->name('my-cases-edit');
Route::match(['post','put','patch'],'/dashboard/my-cases/{case}/update', UpdateMyCase::class)->name('my-cases-update');
