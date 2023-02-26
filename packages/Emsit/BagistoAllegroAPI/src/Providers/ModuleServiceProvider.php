<?php

namespace Emsit\BagistoAllegroAPI\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Emsit\BagistoAllegroAPI\Models\AllegroApiSettings::class,
        \Emsit\BagistoAllegroAPI\Models\AllegroApiToken::class,
        \Emsit\BagistoAllegroAPI\Models\AllegroProductData::class
    ];
}