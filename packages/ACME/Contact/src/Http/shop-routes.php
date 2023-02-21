<?php

Route::group([
        'prefix'     => 'contact',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'ACME\Contact\Http\Controllers\Shop\ContactController@index')->defaults('_config', [
            'view' => 'contact::shop.index',
        ])->name('shop.contact.index');

});