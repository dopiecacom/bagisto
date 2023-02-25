<?php

namespace Emsit\BagistoAllegroAPI\Http\Controllers\Admin;

use Emsit\BagistoAllegroAPI\Repositories\AllegroApiSettingsRepository;
use Emsit\BagistoAllegroAPI\Services\APIAuthenticationService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AllegroAPIAuthenticationController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $apiSettings;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private readonly AllegroApiSettingsRepository $allegroApiSettings)
    {
        $this->middleware('admin');

        $this->apiSettings = $this->allegroApiSettings->first();
    }

    /**
     * @return RedirectResponse
     * @throws Exception
     */
    public function getToken(): RedirectResponse
    {
        $codeVerifier = $this->apiSettings->code_verifier;

        if (isset($_GET["code"])) {
            resolve(APIAuthenticationService::class)->generateAccessToken($_GET["code"], $codeVerifier);
        } else {
            session()->flash('error', 'No verification code provided. Use link in the form in order to generate access token.');
        }

        return redirect()->back();
    }
}
