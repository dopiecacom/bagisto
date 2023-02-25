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
        $apiSettings = $this->allegroApiSettings->first();

        $data = [
            'client_id'     => $apiSettings?->client_id,
            'client_secret' => $apiSettings?->client_secret,
            'sandbox_mode'  => $apiSettings?->sandbox_mode
        ];

        if ($apiSettings?->client_id != null && $apiSettings?->client_secret != null) {
            $data['authUri'] = resolve(APIAuthenticationService::class)->getAuthorizationCode();
        }

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
}
