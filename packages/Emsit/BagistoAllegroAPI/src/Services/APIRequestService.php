<?php

namespace Emsit\BagistoAllegroAPI\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class APIRequestService
{
    public string $authUri;
    public string $tokenUri;

    public function __construct()
    {
        if (1>0) {
            $this->authUri = 'https://allegro.pl.allegrosandbox.pl/auth/oauth/authorize';
            $this->tokenUri = 'https://allegro.pl.allegrosandbox.pl/auth/oauth/token';
        } else {
            $this->authUri = 'https://allegro.pl/auth/oauth/authorize';
            $this->tokenUri = 'https://allegro.pl/auth/oauth/token';
        }
    }

    /**
     * @param array $headers
     * @param array $content
     * @return mixed
     * @throws GuzzleException
     */
    public function tokenRequest(string $content)
    {
        /*
        $client = new Client();

        $response = $client->request('POST', $this->tokenUri, [
            'headers'     => $headers,
            'form_params' => $content,
        ]);
*/
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $this->tokenUri,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $content
            ));

        $tokenResult = curl_exec($ch);
        $resultCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
dd($tokenResult);
        #return json_decode($response);
    }
}