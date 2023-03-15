<?php

namespace Emsit\BagistoInPostShipping\Database\Seeders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaczkomatyLocationsSeeder extends Seeder
{
    /**
     * @throws GuzzleException
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('emsit_paczkomaty_locations')->delete();

        $locations = $this->getLocations(['page' => 1]);

        for ($i = 1; $i <= $locations->total_pages; $i++) {
            foreach ($locations->items as $location) {
                DB::table('emsit_paczkomaty_locations')->insert([
                    [
                        'name'                 => $location->name,
                        'address'              => $location->address->line1,
                        'city'                 => $location->address_details->city,
                        'post_code'            => $location->address_details->post_code,
                        'province'             => $location->address_details->province,
                        'location_description' => $location->location_description,
                    ]
                ]);
            }

            if ($locations->page != $locations->total_pages) {
                $locations = $this->getLocations(['page' => $i + 1]);
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * @param array $query
     * @return \stdClass
     * @throws GuzzleException
     */
    private function getLocations(array $query): \stdClass
    {
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://api-shipx-pl.easypack24.net/v1/points', ['query' => $query]);
        } catch (ConnectException $e) {
            report($e);
        }

        return json_decode($response->getBody()->getContents());
    }
}