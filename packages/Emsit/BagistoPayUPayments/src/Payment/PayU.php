<?php

namespace Emsit\BagistoPayUPayments\Payment;

use Webkul\Payment\Payment\Payment;

class PayU extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'payu';

    public function getRedirectUrl()
    {
        return route('payu.process');
    }
}