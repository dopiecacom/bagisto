<?php

Route::group([
        'prefix'        => 'admin/bagistoallegroapi',
        'middleware'    => ['web', 'admin']
    ], function () {

        Route::get('', 'Emsit\BagistoAllegroAPI\Http\Controllers\Admin\BagistoAllegroAPIController@index')->defaults('_config', [
            'view' => 'bagistoallegroapi::admin.index',
        ])->name('admin.bagistoallegroapi.index');

});