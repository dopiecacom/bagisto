<?php

namespace Emsit\BagistoAllegroAPI\Listeners;

use Carbon\Carbon;
use Emsit\BagistoAllegroAPI\Repositories\AllegroApiTokenRepository;
use Emsit\BagistoAllegroAPI\Repositories\AllegroProductDataRepository;
use Emsit\BagistoAllegroAPI\Services\APIAuthenticationService;
use Emsit\BagistoAllegroAPI\Services\APIRequestsService;
use Prettus\Validator\Exceptions\ValidatorException;

class APIRequestsListener
{
    private string|null $token;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private readonly AllegroProductDataRepository $allegroProductData,
        private readonly APIAuthenticationService $apiAuthenticationService,
        private readonly APIRequestsService $apiRequestsService
    )
    {
        //
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
        $this->token = $this->apiAuthenticationService->getToken();

        if ($this->token) {
            $this->apiRequestsService->createOffer($this->token, $event->sku, $event->id);
        }
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handleUpdate(object $event): void
    {
        $this->token = $this->apiAuthenticationService->getToken();

        if ($this->token == null) {
            return;
        }

        $product = $event->product_flats->first();

        $offerId = $this->allegroProductData->where('shop_product_id', $product->product_id)
            ->first()
            ->getAttribute('allegro_product_id');

        $values = collect([
            'name'        => $event->name,
            'description' => $event->description,
            'images'      => $event->images,
            'price'       => round($event->price, 2),
            'stock'       => $event->totalQuantity(),
            'location'    => collect([
                'country'  => core()->getConfigData('sales.shipping.origin.country'),
                'state'    => core()->getConfigData('sales.shipping.origin.state'),
                'city'     => core()->getConfigData('sales.shipping.origin.city'),
                'address1' => core()->getConfigData('sales.shipping.origin.address1'),
                'zipcode'  => core()->getConfigData('sales.shipping.origin.zipcode')
            ])
        ]);

        $this->apiRequestsService->updateOffer($this->token, $offerId, $values);
    }
}
