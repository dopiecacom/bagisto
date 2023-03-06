<?php

Route::group(['prefix' => 'admin/contact', 'middleware' => ['web', 'admin']], function () {

    Route::get('', [RKREZA\Contact\Http\Controllers\ContactController::class, 'index'])
        ->defaults('_config', ['view' => 'contact_view::contact.index'])
        ->name('admin.contact.index');

    Route::get('view/{id}', [RKREZA\Contact\Http\Controllers\ContactController::class, 'view'])
        ->defaults('_config', ['view' => 'contact_view::contact.view'])
        ->name('admin.contact.view');

    Route::post('delete/{id}', [RKREZA\Contact\Http\Controllers\ContactController::class, 'destroy'])
        ->name('admin.contact.delete');

});

Route::group(['middleware' => ['web', 'locale', 'theme', 'currency']], function () {

// Registration Routes

    Route::get('/contact', 'RKREZA\Contact\Http\Controllers\ContactController@show')
    		->defaults('_config', ['view' => 'contact_view::contact.shop.index'])
    		->name('shop.contact.index');

    Route::post('/contact', 'RKREZA\Contact\Http\Controllers\ContactController@sendMessage')
    		->defaults('_config',['redirect' => 'shop.contact.index'])
    		->name('shop.contact.send-message');

});