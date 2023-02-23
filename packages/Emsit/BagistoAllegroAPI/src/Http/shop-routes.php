<?php

Route::group([
        'prefix'     => 'bagistoallegroapi',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'Emsit\BagistoAllegroAPI\Http\Controllers\Shop\BagistoAllegroAPIController@index')->defaults('_config', [
            'view' => 'bagistoallegroapi::shop.index',
        ])->name('shop.bagistoallegroapi.index');

});