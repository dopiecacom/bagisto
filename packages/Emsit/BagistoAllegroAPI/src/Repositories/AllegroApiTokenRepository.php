<?php

namespace Emsit\BagistoAllegroAPI\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Emsit\BagistoAllegroAPI\Models\AllegroApiToken;

class AllegroApiTokenRepository extends Repository
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    public function model()
    {
        return 'Emsit\BagistoAllegroAPI\Contracts\AllegroApiToken';
    }

    public function update(array $data, $id, $attribute = "id")
    {
        $apiSettings = $this->find($id);
        $apiSettings->update($data);

        return $apiSettings;
    }
}