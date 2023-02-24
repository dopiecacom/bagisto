<?php

Route::group(['prefix' => 'admin/bagistoallegroapi', 'middleware' => ['web', 'admin']], function () {

    Route::get('', [Emsit\BagistoAllegroAPI\Http\Controllers\Admin\BagistoAllegroAPIController::class, 'index'])
        ->defaults('_config', ['view' => 'bagistoallegroapi::admin.index',])
        ->name('admin.bagistoallegroapi.index');

    Route::post('update', [Emsit\BagistoAllegroAPI\Http\Controllers\Admin\BagistoAllegroAPIController::class, 'update'])
        ->name('admin.bagistoallegroapi.update');

    Route::get('auth', [Emsit\BagistoAllegroAPI\Http\Controllers\Admin\AllegroAPIAuthenticationController::class, 'getToken'])
        ->defaults('_config', ['view' => 'bagistoallegroapi::admin.auth',])
        ->name('admin.bagistoallegroapi.auth');

});