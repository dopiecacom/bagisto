<?php

namespace Emsit\BagistoInPostShipping\Repositories;

use Emsit\BagistoInPostShipping\Contracts\PaczkomatyLocation;
use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;

class PaczkomatyLocationRepository extends Repository
{
    /**
     * Create a new repository instance.
     *
     * @param  App $app
     * @return void
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model(): string
    {
        return 'Emsit\BagistoInPostShipping\Contracts\PaczkomatyLocation';
    }

    /**
     * @param  array  $data
     * @return PaczkomatyLocation
     */
    public function create(array $data): PaczkomatyLocation
    {
        return $this->model->create($data);
    }

    /**
     * @param  array  $data
     * @param  int  $id
     * @param  string  $attribute
     * @return PaczkomatyLocation
     */
    public function update(array $data, $id, $attribute = "id"): PaczkomatyLocation
    {
        $family = $this->find($id);

        $family->update($data);

        return $family;
    }
}