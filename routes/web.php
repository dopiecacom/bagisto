<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

#Route::get('payu-success', [Emsit\BagistoPayUPayments\Http\Controllers\Shop\BagistoPayUPaymentsController::class, 'success'])->name('payu.success');