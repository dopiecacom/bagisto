<?php

namespace Emsit\BagistoAllegroAPI\Listeners;

use Carbon\Carbon;
use Emsit\BagistoAllegroAPI\Repositories\AllegroApiTokenRepository;
use Emsit\BagistoAllegroApi\Services\APIAuthenticationService;
use Emsit\BagistoAllegroApi\Services\APIRequestsService;
use Prettus\Validator\Exceptions\ValidatorException;

class TestListener
{
    private string $token;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private readonly AllegroApiTokenRepository $allegroApiTokens,
        #private readonly APIAuthenticationService $apiAuthenticationService,
        #private readonly APIRequestsService $apiRequestsService
    )
    {
        $accessToken = $this->allegroApiTokens->orderBy('id', 'desc')->first();

        if ($accessToken->token_expiration_date < Carbon::now()) {
            $this->token = $this->apiAuthenticationService->refreshAccessToken($accessToken->refresh_token);
        } else {
            $this->token = $accessToken->token;
        }
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     * @throws ValidatorException
     */
    public function handleCreate(object $event): void
    {
        #$this->apiRequestsService->createOffer($this->token, $event->sku, $event->id);
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handleUpdate(object $event): void
    {
        $product = $event->product_flats->first();

        $offerId = AllegroProductData::where('shop_product_id', $product->product_id)
            ->first()
            ->getAttribute('allegro_product_id');

        $values = collect([
            'price' => round($product->price, 2)
        ]);

        $this->apiRequestsService->updateOffer($this->token, $offerId, $values);
    }
}
