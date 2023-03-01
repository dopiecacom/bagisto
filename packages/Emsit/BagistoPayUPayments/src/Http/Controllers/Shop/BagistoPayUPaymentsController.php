<?php

namespace Emsit\BagistoPayUPayments\Http\Controllers\Shop;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Omnipay\Omnipay;
use Omnipay\PayU\GatewayFactory;
use Omnipay\PayU\Messages\PurchaseRequest;
use OpenPayU_Configuration;
use OpenPayU_Order;
use Prettus\Validator\Exceptions\ValidatorException;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderRepository;

class BagistoPayUPaymentsController extends Controller
{
    use DispatchesJobs, ValidatesRequests;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * OrderRepository $orderRepository
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * InvoiceRepository $invoiceRepository
     *
     * @var \Webkul\Sales\Repositories\InvoiceRepository
     */
    protected $invoiceRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $orderRepository,  InvoiceRepository $invoiceRepository)
    {
        $this->_config = request('_config');
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * @return RedirectResponse
     */
    public function redirect(): RedirectResponse
    {
        $posId = core()->getConfigData('sales.paymentmethods.payu.payu_pos_id');
        $secondKey = core()->getConfigData('sales.paymentmethods.payu.payu_second_key');
        $oAuthClientSecret = core()->getConfigData('sales.paymentmethods.payu.payu_client_secret') . '11';

        if (core()->getConfigData('sales.paymentmethods.payu.payu-website') == "Sandbox") :
            $isSandbox = true;
        else :
            $isSandbox = false;
        endif;

        $gateway = GatewayFactory::createInstance($posId, $secondKey, $oAuthClientSecret, $isSandbox);

        try {
            $cart = Cart::getCart();

            $orderNo = $cart->id;
            $returnUrl = route('payu.success');
            $description = 'Bagisto order no. ' . $cart->id;

            $billingAddress = $cart->billing_address;

            $shippingRate = $cart->selected_shipping_rate ? $cart->selected_shipping_rate->price : 0; // shipping rate
            $discountAmount = $cart->discount_amount; // discount amount
            $totalAmount =  ($cart->sub_total + $cart->tax_total + $shippingRate) - $discountAmount; // total amount

            foreach ($cart->items as $key => $item) {
                $items[$key]['name'] = $item->name;
                $items[$key]['unitPrice'] = round($item->price, 2);
                $items[$key]['quantity'] = $item->quantity;
            }

            $purchaseRequest = [
                'continueUrl'   => route('payu.success'),
                'customerIp'    => '127.0.0.1',
                'merchantPosId' => $posId,
                'description'   => $description,
                'currencyCode'  => 'PLN',
                'totalAmount'   => $totalAmount,
                'exOrderId'     => $orderNo,
                'buyer'         => [
                    'email'     => $billingAddress->email,
                    'firstName' => $billingAddress->first_name,
                    'lastName'  => $billingAddress->last_name,
                    'phone'     => $billingAddress->phone
                ],
                'products'      => $items,
            ];

            $response = $gateway->purchase(['purchaseData' => $purchaseRequest]);

            // Payment init OK, redirect to the payment gateway
            return redirect($response->getRedirectUrl());
        } catch (\Exception $e) {
            return $this->failure();
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidatorException
     */
    public function success(Request $request): RedirectResponse
    {
        // ToDo: Improve error verification

        if (isset($_GET['error'])) {
            return $this->failure();
        }

        $order = $this->orderRepository->create(Cart::prepareDataForOrder());
        $this->orderRepository->update(['status' => 'processing'], $order->id);

        if ($order->canInvoice()) {
            $this->invoiceRepository->create($this->prepareInvoiceData($order));
        }

        Cart::deActivateCart();

        session()->flash('order', $order);

        return redirect()->route('shop.checkout.success');
    }

    /**
     * @return RedirectResponse
     */
    public function failure(): RedirectResponse
    {
        session()->flash('error', __('Payu payment either cancelled or transaction failure.'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @param $order
     * @return array
     */
    protected function prepareInvoiceData($order): array
    {
        $invoiceData = ["order_id" => $order->id,];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
