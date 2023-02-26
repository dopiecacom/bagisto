<?php

namespace Emsit\BagistoAllegroAPI\Services;

use Carbon\Carbon;
use Emsit\BagistoAllegroAPI\Repositories\AllegroApiSettingsRepository;
use Emsit\BagistoAllegroAPI\Repositories\AllegroApiTokenRepository;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Prettus\Validator\Exceptions\ValidatorException;

class APIAuthenticationService
{
    public string $authUri;
    public string $tokenUri;

    protected $apiSettings;

    private readonly string $clientId;
    private readonly string $clientSecret;
    private string $redirectUri;

    public function __construct(
        private readonly AllegroApiSettingsRepository $allegroApiSettings,
        private readonly AllegroApiTokenRepository $allegroApiToken
    ) {
        $this->apiSettings = $this->allegroApiSettings->first();

        $this->clientId = $this->apiSettings->client_id;
        $this->clientSecret = $this->apiSettings->client_secret;
        $this->redirectUri = route('admin.bagistoallegroapi.auth') . '/';

        if ($this->apiSettings->sandbox_mode) {
            $this->authUri = 'https://allegro.pl.allegrosandbox.pl/auth/oauth/authorize';
            $this->tokenUri = 'https://allegro.pl.allegrosandbox.pl/auth/oauth/token';
        } else {
            $this->authUri = 'https://allegro.pl/auth/oauth/authorize';
            $this->tokenUri = 'https://allegro.pl/auth/oauth/token';
        }
    }

    /**
     * @param array $content
     * @param array $headers
     * @return mixed
     */
    private function tokenRequest(array $content, array $headers = array()): mixed
    {
        $client = new Client();

        try {
            $response = $client->post($this->tokenUri, [
                'headers'     => $headers,
                'form_params' => $content,
            ]);
        } catch (ConnectException $ex) {
            $response = 'Connection with API failed';
        } catch (GuzzleException $ex) {
            $response = $ex->getResponse();
        }

        return $response;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function generateCodeVerifier(): string
    {
        $verifierBytes = random_bytes(80);

        return rtrim(strtr(base64_encode($verifierBytes), "+/", "-_"), "=");
    }

    /**
     * @param string|null $codeVerifier
     * @return string
     * @throws Exception
     */
    private function generateCodeChallenge(string $codeVerifier = null): string
    {
        if ($codeVerifier == null) {
            $codeVerifier = $this->generateCodeVerifier();

            $this->allegroApiSettings->update(['code_verifier' => $codeVerifier], $this->apiSettings->id);
        }

        $challengeBytes = hash("sha256", $codeVerifier, true);

        return rtrim(strtr(base64_encode($challengeBytes), "+/", "-_"), "=");
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getAuthorizationCode(): string
    {
        $codeChallenge = $this->generateCodeChallenge();

        return $this->authUri . "?response_type=code&client_id=" . $this->clientId .
            "&redirect_uri=" . $this->redirectUri .
            "&code_challenge_method=S256&code_challenge=" . $codeChallenge;
    }

    /**
     * @param string $authorizationCode
     * @param string $codeVerifier
     * @return void
     * @throws GuzzleException|ValidatorException
     */
    public function generateAccessToken(string $authorizationCode, string $codeVerifier): void
    {
        $authorizationCode = urlencode($authorizationCode);

        $content = [
            'grant_type'    => 'authorization_code',
            'code'          => $authorizationCode,
            'redirect_uri'  => $this->redirectUri,
            'code_verifier' => $codeVerifier
        ];

        $response = $this->tokenRequest($content);

        switch ($response->getStatusCode()) {
            case 200:
                $tokenResult = json_decode($response->getBody()->getContents());

                $this->allegroApiToken->create([
                    'token'                 => $tokenResult->access_token,
                    'refresh_token'         => $tokenResult->refresh_token,
                    'token_expiration_date' => Carbon::now()->addSeconds($tokenResult->expires_in)->toDateTimeString()
                ]);

                session()->flash('success', 'Access token has been generated and saved in the database.');

                break;
            default:
                session()->flash('error', $response->getReasonPhrase() . 'Check your credentials, refresh the page and try again.');

                break;
        }
    }

    /**
     * @param string $refreshToken
     * @return string
     */
    public function refreshAccessToken(string $refreshToken): string
    {
        $authorization = base64_encode($this->clientId . ':' . $this->clientSecret);

        $headers = [
            'Authorization' => "Basic " . $authorization,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $content = [
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
            'redirect_uri'  => $this->redirectUri
        ];

        $response = $this->tokenRequest($content, $headers);

        switch ($response->getStatusCode()) {
            case 200:
                $tokenResult = json_decode($response->getBody()->getContents());

                $lastToken = $this->allegroApiToken->orderBy('id', 'desc')->first();

                $lastToken->update([
                    'token'                 => $tokenResult->access_token,
                    'refresh_token'         => $tokenResult->refresh_token,
                    'token_expiration_date' => Carbon::now()->addSeconds($tokenResult->expires_in)->toDateTimeString()
                ]);

                return $tokenResult->access_token;
            default:
                report('Something went wrong: ' . $response->getStatusCode() . ': ' . $response->getReasonPhrase());

                return '';
        }
    }
}