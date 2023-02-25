<?php

namespace Emsit\BagistoAllegroAPI\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Emsit\BagistoAllegroAPI\Models\AllegroProductData;

class AllegroProductDataRepository extends Repository
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'Emsit\BagistoAllegroAPI\Contracts\AllegroProductData';
    }

    public function update(array $data, $id, $attribute = "id")
    {
        $apiSettings = $this->find($id);
        $apiSettings->update($data);

        return $apiSettings;
    }
}