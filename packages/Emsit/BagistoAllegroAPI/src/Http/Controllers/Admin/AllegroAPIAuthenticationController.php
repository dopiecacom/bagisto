<?php

namespace Emsit\BagistoAllegroAPI\Http\Controllers\Admin;

use GuzzleHttp\Client;
use Emsit\BagistoAllegroAPI\Repositories\AllegroApiSettingsRepository;
use Emsit\BagistoAllegroAPI\Services\APIRequestService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AllegroAPIAuthenticationController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $apiSettings;

    private readonly bool $sandboxMode;
    private readonly string $clientId;
    private readonly string $clientSecret;
    private string $redirectUri;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly AllegroApiSettingsRepository $allegroApiSettings,
        private readonly APIRequestService $apiRequestService
    ) {
        $this->middleware('admin');

        $this->apiSettings = $this->allegroApiSettings->first();

        $this->clientId = $this->apiSettings->client_id;
        $this->clientSecret = $this->apiSettings->client_secret;
        $this->redirectUri = route('admin.bagistoallegroapi.auth');
        $this->sandboxMode = $this->apiSettings->sandbox_mode;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function getToken(): void
    {
        if (!$this->apiSettings->code_verifier) {
            $codeVerifier = $this->generateCodeVerifier();
        } else {
            $codeVerifier = $this->apiSettings->code_verifier;
        }

        if (isset($_GET["code"])) {
            $accessToken = $this->getAccessToken($_GET["code"], $codeVerifier);
        } else {
            $this->getAuthorizationCode($codeVerifier);
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateCodeVerifier(): string
    {
        $verifierBytes = random_bytes(80);

        return rtrim(strtr(base64_encode($verifierBytes), "+/", "-_"), "=");
    }

    /**
     * @param string|null $codeVerifier
     * @return string
     * @throws \Exception
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
     * @throws \Exception
     */
    public function getAuthorizationCode(): string
    {
        $codeChallenge = $this->generateCodeChallenge();

        if ($this->sandboxMode) {
            $authUri = 'https://allegro.pl.allegrosandbox.pl/auth/oauth/authorize';
        } else {
            $authUri = 'https://allegro.pl/auth/oauth/authorize';
        }

        return $authUri . "?response_type=code&client_id=" . $this->clientId .
            "&redirect_uri=" . $this->redirectUri . '/' .
            "&code_challenge_method=S256&code_challenge=" . $codeChallenge .
            "&prompt=none";
    }

    /**
     * @param string $authorizationCode
     * @param string $codeVerifier
     * @return void
     * @throws GuzzleException
     */
    private function getAccessToken(string $authorizationCode, string $codeVerifier)
    {
        $authorizationCode = urlencode($authorizationCode);
        $content = "grant_type=authorization_code&code=${authorizationCode}&redirect_uri=http://localhost:8000/admin/bagistoallegroapi/auth/" . "&code_verifier=${codeVerifier}";
        /*


        $content = [
            'grant_type'    => 'authorization_code',
            'code'          => $authorizationCode,
            'redirect_uri'  => $this->redirectUri,
            'code_verifier' => $codeVerifier
        ];
*/
        $tokenResult = $this->apiRequestService->tokenRequest($content);
dd($tokenResult);
        return json_decode($tokenResult);
    }
}
