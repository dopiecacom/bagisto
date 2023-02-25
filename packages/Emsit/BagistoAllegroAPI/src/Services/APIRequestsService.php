<?php

namespace Emsit\BagistoAllegroAPI\Services;

use Emsit\BagistoAllegroAPI\Repositories\AllegroApiSettingsRepository;
use Emsit\BagistoAllegroAPI\Repositories\AllegroProductDataRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Prettus\Validator\Exceptions\ValidatorException;

class APIRequestsService
{
    private mixed $apiSettings;
    private string $environmentUri;

    public function __construct(
        private readonly AllegroApiSettingsRepository $allegroApiSettings,
        private readonly AllegroProductDataRepository $allegroProductData
    )
    {
        $this->apiSettings = $this->allegroApiSettings->first();

        if ($this->apiSettings->sandbox_mode) {
            $this->environmentUri = 'https://allegro.pl.allegrosandbox.pl/';
        } else {
            $this->environmentUri = 'https://allegro.pl/';
        }
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $content
     * @param string $method
     * @return mixed
     */
    private function apiRequest(string $url, array $headers, array $content = array(), string $method = 'POST'): mixed
    {
        $client = new Client();

        try {
            $response = $client->request($method, $url, [
                'headers'     => $headers,
                'form_params' => $content,
            ]);
        } catch (GuzzleException $ex) {
            $response = $ex->getResponse();
        }

        return $response;
    }


    /**
     * @param string $token
     * @param string $sku
     * @param int $productId
     * @return void
     * @throws ValidatorException
     */
    public function createOffer(string $token, string $sku, int $productId): void
    {
        $url = $this->environmentUri . "sale/product-offers";

        $headers = array(
            "Authorization: Bearer {$token}",
            "Accept: application/vnd.allegro.public.v1+json",
            "Content-Type: application/vnd.allegro.public.v1+json"
        );

        $content = array(
            "name" => "Ajfon",
            "productSet" => [
                [
                    "product" => [
                        "id" => "$sku"
                    ]
                ]
            ],
            "sellingMode" => [
                "price" => [
                    "amount" => 1000,
                    "currency" => "PLN"
                ]
            ],
            "location" => [
                "city" => "Warszawa",
                "countryCode" => "PL",
                "postCode" => "00-121",
                "province" => "WIELKOPOLSKIE"
            ],
            "stock" => [
                "available" => 10
            ],
        );

        $response = $this->apiRequest($url, $headers, $content);

        $this->allegroProductData->create([
            'allegro_product_id' => $response->id,
            'shop_product_id'    => $productId
        ]);
    }

    public function updateOffer(string $token, int|string $offerId, Collection $values)
    {
        $headers = array("Authorization: Bearer {$token}", "Accept: application/vnd.allegro.public.v1+json", "Content-Type: application/vnd.allegro.public.v1+json");
        $url = $this->environmentUri . "sale/product-offers/$offerId";

        $content = array(
            "sellingMode" => [
                "price" => [
                    "amount" => $values->get('price'),
                    "currency" => "PLN"
                ]
            ],
            "stock" => [
                "available" => 10
            ],
        );
        dd($content);
        $ch = $this->getCurl($headers, $url, json_encode($content), 'PATCH');
        $result = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return json_decode($result);
    }

    public function getProducts($token)
    {
        $headers = array("Authorization: Bearer {$token}", "Accept: application/vnd.allegro.public.v1+json");
        $url = "https://api.allegro.pl.allegrosandbox.pl/sale/offers";
        $ch = $this->getCurl($headers, $url);
        $mainCategoriesResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($mainCategoriesResult === false || $resultCode !== 200) {
            var_dump($mainCategoriesResult);
            exit ("Something went wrong");
        }
        $categoriesList = json_decode($mainCategoriesResult);
        return $categoriesList;
    }
}