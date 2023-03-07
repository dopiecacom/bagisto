<?php

namespace Emsit\BagistoAllegroAPI\Services;

use Emsit\BagistoAllegroAPI\Repositories\AllegroApiSettingsRepository;
use Emsit\BagistoAllegroAPI\Repositories\AllegroProductDataRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Prettus\Validator\Exceptions\ValidatorException;

class APIRequestsService
{
    private Client $client;
    private mixed $apiSettings;
    private string $environmentUri;
    private string $uploadUri;

    public function __construct(
        private readonly AllegroApiSettingsRepository $allegroApiSettings,
        private readonly AllegroProductDataRepository $allegroProductData
    ) {
        $this->apiSettings = $this->allegroApiSettings->first();

        if ($this->apiSettings->sandbox_mode) {
            $this->environmentUri = 'https://api.allegro.pl.allegrosandbox.pl/';
            $this->uploadUri = 'https://upload.allegro.pl.allegrosandbox.pl/';
        } else {
            $this->environmentUri = 'https://api.allegro.pl/';
            $this->uploadUri = 'https://upload.allegro.pl';
        }

        $this->client = new Client();
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
        try {
            $response = $this->client->request($method, $url, [
                'headers' => $headers,
                'json' => $content,
            ]);
        } catch (ConnectException $ex) {
            $response = 'Connection with API failed';
        } catch (GuzzleException $ex) {
            $response = $ex->getResponse();
        }

        return $response;
    }

    /**
     * @param string $token
     * @param Collection $images
     * @return Collection
     */
    private function imageUpload(string $token, Collection $images): mixed
    {
        try {
            $headers = [
                'Authorization' => "Bearer " . $token,
                'Accept' => 'application/vnd.allegro.public.v1+json',
                'Content-Type' => 'image/jpeg', 'image/png', 'image/webp'
            ];

            $responseArray = [];

            foreach ($images as $image) {
                $imagePath = public_path() . '/storage/' . $image->getAttribute('path');

                $response = $this->client->post($this->uploadUri . 'sale/images', [
                    'headers' => $headers,
                    'body'    => file_get_contents($imagePath),
                ])->getBody()->getContents();

                $responseArray[] = json_decode($response);
            }

            foreach ($responseArray as $locationUri) {
                $locationUris[] = $locationUri->location;
            }

            return $locationUris;
        } catch (ConnectException $ex) {
            $response = 'Connection with API failed';
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

        $headers = [
            'Authorization' => "Bearer " . $token,
            'Accept' => 'application/vnd.allegro.public.v1+json',
            'Content-Type' => 'application/vnd.allegro.public.v1+json'
        ];

        $content = array(
            "name" => "Bagisto Offer",
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
            "publication" => [
                "status" => "INACTIVE"
            ]
        );

        $response = $this->apiRequest($url, $headers, $content);

        if ($response->getStatusCode() == 201 || $response->getStatusCode() == 202){
            $response = json_decode($response->getBody()->getContents());

            $this->allegroProductData->create([
                'allegro_product_id' => $response->id,
                'shop_product_id'    => $productId
            ]);
        } else {
            // ToDo: Handle exceptions
            report(json_decode($response->getBody()->getContents(true))->errors[0]->userMessage);
        }
    }

    /**
     * @param string $token
     * @param int|string $offerId
     * @param Collection $values
     * @return void
     */
    public function updateOffer(string $token, int|string $offerId, Collection $values): void
    {
        $url = $this->environmentUri . "sale/product-offers/$offerId";

        $headers = [
            'Authorization' => "Bearer " . $token,
            'Accept' => 'application/vnd.allegro.public.v1+json',
            'Content-Type' => 'application/vnd.allegro.public.v1+json'
        ];

        $content = array(
            "name" => $values->get('name'),
            "sellingMode" => [
                "price" => [
                    "amount" => $values->get('price'),
                    "currency" => "PLN"
                ]
            ],
            "description" => [
                "sections" => [
                    [
                        "items" => [
                            [
                                "type"    => "TEXT",
                                "content" => $values->get('description')
                            ]
                        ]
                    ]
                ]
            ],
            "location" => [
                "city" => $values->get('location')->get('city'),
                "countryCode" => $values->get('location')->get('country'),
                "postCode" => $values->get('location')->get('zipcode'),
                "province" => str_replace(array('-', ' '), '_', strtoupper($values->get('location')->get('state')))
            ],
            "stock" => [
                "available" => $values->get('stock')
            ],
            "publication" => [
                "status" => "ACTIVE"
            ]
        );

        if ($values->get('images')->isNotEmpty()) {
            $content["images"] = $this->imageUpload($token, $values->get('images'));
        }

        $response = $this->apiRequest($url, $headers, $content, 'PATCH');

        // ToDo: Handle exceptions
        if ($response->getStatusCode() == 200 || $response->getStatusCode() == 202){
            $response = json_decode($response->getBody()->getContents());
        } else {
            report(json_decode($response->getBody()->getContents(true))->errors[0]->userMessage);
        }
    }
}