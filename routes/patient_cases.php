<?php

use App\Http\Controllers\Cases\DeleteCasePricing;
use App\Http\Controllers\Cases\ListCasePricing;
use App\Http\Controllers\Cases\StoreCasePricing;
use App\Http\Controllers\Cases\UpdateCasePricing;
use App\Http\Controllers\MyCases\CreateCase;
use App\Http\Controllers\MyCases\EditCase;
use App\Http\Controllers\MyCases\GetMyCaseInvoiceData;
use App\Http\Controllers\MyCases\MyCases;
use App\Http\Controllers\MyCases\PayMyCaseInvoice;
use App\Http\Controllers\MyCases\StoreMyCase;
use App\Http\Controllers\MyCases\UpdateMyCase;
use App\Http\Controllers\PatientCase\ListAllPatientCases;
use App\Http\Controllers\Cases\UnassignedCasesController;
use App\Http\Controllers\Cases\AssignedCasesController;
use App\Http\Controllers\Cases\PatientCasesListController;
use App\Http\Controllers\Cases\AcceptCaseController;
use App\Http\Controllers\Cases\RejectCaseController;
use App\Http\Controllers\Cases\CompleteCaseController;
use App\Http\Controllers\Cases\CancelCaseController;
use App\Http\Controllers\Cases\RequestMoreInfoController;
use App\Http\Controllers\Cases\SecondReviewController;
use App\Http\Controllers\PatientCase\ViewPatientCase;
use App\Http\Controllers\Payments\MakePaymentController;
use App\Http\Controllers\Payments\UserBalanceController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard/all-system-patient-cases', ListAllPatientCases::class)->name('all-patient-cases');

Route::get('/dashboard/my-cases', MyCases::class)->name('my-cases');
Route::get('/dashboard/my-cases/create', CreateCase::class)->name('my-cases-create');
Route::post('/dashboard/my-cases/store', StoreMyCase::class)->name('my-cases-store');
Route::get('/dashboard/my-cases/{case}/edit', EditCase::class)->name('my-cases-edit');
Route::match(['post','put','patch'],'/dashboard/my-cases/{case}/update', UpdateMyCase::class)->name('my-cases-update');

// Case Filtering Views
Route::get('/dashboard/unassigned-cases', UnassignedCasesController::class)->name('unassigned-cases')->middleware(['auth', 'verified']);
Route::get('/dashboard/assigned-cases', AssignedCasesController::class)->name('assigned-cases')->middleware(['auth', 'verified']);
Route::get('/dashboard/patient-cases-list', PatientCasesListController::class)->name('patient-cases-list')->middleware(['auth', 'verified']);


// case pricing

Route::get('/dashboard/case-pricing', ListCasePricing::class)->name('case-pricing')->middleware(['auth', 'verified']);
Route::post('/dashboard/case-pricing/store', StoreCasePricing::class)->name('case-pricing-store')->middleware(['auth', 'verified']);
Route::match(['put','post'],'/dashboard/case-pricing/{pricing}/update', UpdateCasePricing::class)->name('case-pricing-update')->middleware(['auth', 'verified']);
Route::delete('/dashboard/case-pricing/{pricing}/delete', DeleteCasePricing::class)->name('case-pricing-delete')->middleware(['auth', 'verified']);


// Case Actions
Route::get('/dashboard/cases/{case}', ViewPatientCase::class)->name('case-view')->middleware(['auth', 'verified']);
Route::get('/dashboard/get/case/{case}/invoice', GetMyCaseInvoiceData::class)->name('get-case-invoice-data')->middleware(['auth', 'verified']);

Route::post('/dashboard/pay/case/{case}/invoice', PayMyCaseInvoice::class)->name('pay-my-case-invoice')->middleware(['auth', 'verified']);

Route::post('/dashboard/cases/{case}/accept', AcceptCaseController::class)->name('case-accept')->middleware(['auth', 'verified']);
Route::post('/dashboard/cases/{case}/reject', RejectCaseController::class)->name('case-reject')->middleware(['auth', 'verified']);
Route::post('/dashboard/cases/{case}/complete', CompleteCaseController::class)->name('case-complete')->middleware(['auth', 'verified']);
Route::post('/dashboard/cases/{case}/cancel', CancelCaseController::class)->name('case-cancel')->middleware(['auth', 'verified']);
Route::post('/dashboard/cases/{case}/request-info', RequestMoreInfoController::class)->name('case-request-info')->middleware(['auth', 'verified']);
Route::post('/dashboard/cases/{case}/second-review', SecondReviewController::class)->name('case-second-review')->middleware(['auth', 'verified']);

// Payments
Route::post('/dashboard/invoices/{invoice}/payment', MakePaymentController::class)->name('invoice-payment')->middleware(['auth', 'verified']);
Route::get('/dashboard/balance', UserBalanceController::class)->name('balance')->middleware(['auth', 'verified']);

