<?php

Route::group([
        'prefix'        => 'admin/contact',
        'middleware'    => ['web', 'admin']
    ], function () {

        Route::get('', 'ACME\Contact\Http\Controllers\Admin\ContactController@index')->defaults('_config', [
            'view' => 'contact::admin.index',
        ])->name('admin.contact.index');

});