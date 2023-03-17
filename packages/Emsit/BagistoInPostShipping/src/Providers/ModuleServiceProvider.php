<?php

namespace Emsit\BagistoInPostShipping\Providers;

use Webkul\Core\Providers\CoreModuleServiceProvider;

class ModuleServiceProvider extends CoreModuleServiceProvider
{
    protected $models = [
        \Emsit\BagistoInPostShipping\Models\PaczkomatyLocation::class,
    ];
}