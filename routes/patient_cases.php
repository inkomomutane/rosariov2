<?php

use App\Http\Controllers\PatientCase\ListAllPatientCases;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/dashboard/all-system-patient-cases', ListAllPatientCases::class)->name('all-patient-cases');
