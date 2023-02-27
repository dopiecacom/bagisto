<?php

namespace Emsit\BagistoAllegroAPI\Http\Controllers\Admin;

use Emsit\BagistoAllegroAPI\Services\APIAuthenticationService;
use Emsit\BagistoAllegroAPI\Repositories\AllegroApiSettingsRepository;
use Emsit\BagistoAllegroAPI\Repositories\AllegroApiTokenRepository;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Prettus\Validator\Exceptions\ValidatorException;

class BagistoAllegroAPIController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly AllegroApiSettingsRepository $allegroApiSettings,
        private readonly AllegroApiTokenRepository $allegroApiToken
    ) {
        $this->middleware('admin');

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     * @throws Exception
     */
    public function index(): View
    {
        $data = $this->getViewData();

        return view($this->_config['view'], ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidatorException
     */
    public function update(Request $request): RedirectResponse
    {
        $apiSettings = $this->allegroApiSettings->first();

        if ($apiSettings == null) {
            $this->allegroApiSettings->create([
                'client_id'     => $request->client_id,
                'client_secret' => $request->client_secret,
                'sandbox_mode'  => $request->sandbox_mode
            ]);
        } else {
            $data = [
                'client_id'     => $request->client_id,
                'client_secret' => $request->client_secret,
                'sandbox_mode'  => $request->sandbox_mode
            ];
            $this->allegroApiSettings->update($data, $apiSettings->id);
        }

        session()->flash('success', 'API information updated.');

        return redirect()->back();
    }

    /**
     * @return View
     * @throws Exception
     */
    public function getToken(): View
    {
        $data = $this->getViewData();

        $codeVerifier = $data->get('codeVerifier');

        if (isset($_GET["code"])) {
            resolve(APIAuthenticationService::class)->generateAccessToken($_GET["code"], $codeVerifier);
        } else {
            session()->flash('error', 'No verification code provided. Use link in the form in order to generate access token.');
        }

        return view($this->_config['view'], ['data' => $data]);
    }

    /**
     * @return Collection
     */
    protected function getViewData(): Collection
    {
        $apiSettings = $this->allegroApiSettings->first();

        $data = [
            'clientId'     => $apiSettings?->client_id,
            'clientSecret' => $apiSettings?->client_secret,
            'sandboxMode'  => $apiSettings?->sandbox_mode,
            'codeVerifier' => $apiSettings?->code_verifier
        ];

        if ($apiSettings?->client_id != null && $apiSettings?->client_secret != null) {
            $data['authUri'] = resolve(APIAuthenticationService::class)->getAuthorizationCode();
        }

        return collect($data);
    }
}
