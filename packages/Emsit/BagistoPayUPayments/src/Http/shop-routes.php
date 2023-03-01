<?php

Route::group([
    //   'prefix'     => 'payu',
    'middleware' => ['web', 'theme', 'locale', 'currency']
], function () {

    Route::get('payu-redirect', [Emsit\BagistoPayUPayments\Http\Controllers\Shop\BagistoPayUPaymentsController::class, 'redirect'])->name('payu.process');
    Route::get('payu-success', [Emsit\BagistoPayUPayments\Http\Controllers\Shop\BagistoPayUPaymentsController::class, 'success'])->name('payu.success');
    Route::post('payu-failure', [Emsit\BagistoPayUPayments\Http\Controllers\Shop\BagistoPayUPaymentsController::class, 'failure'])->name('payu.failure');

});