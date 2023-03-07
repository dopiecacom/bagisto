<?php

namespace Emsit\BagistoInPostShipping\Carriers;

use Config;
use Webkul\Shipping\Carriers\AbstractShipping;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;

class BagistoInPostShipping extends AbstractShipping
{
    /**
     * Shipping method code
     *
     * @var string
     */
    protected $code  = 'bagistoinpostshipping';

    /**
     * Returns rate for shipping method
     *
     * @return CartShippingRate|false
     */
    public function calculate()
    {
        if (! $this->isAvailable()) {
            return false;
        }

        $object = new CartShippingRate;

        $object->carrier = 'bagistoinpostshipping';
        $object->carrier_title = $this->getConfigData('title');
        $object->method = 'bagistoinpostshipping_bagistoinpostshipping';
        $object->method_title = $this->getConfigData('title');
        $object->method_description = $this->getConfigData('description');
        $object->price = $this->getConfigData('default_rate');
        $object->base_price = $this->getConfigData('default_rate');

        return $object;
    }
}