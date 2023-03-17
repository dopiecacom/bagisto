<?php

use Emsit\BagistoInPostShipping\Repositories\PaczkomatyLocationRepository;
use Illuminate\Http\Request;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartAddress;

Route::group(['prefix' => 'bagistoinpostshipping', 'middleware' => ['web', 'theme', 'locale', 'currency']], function () {

    Route::get('locations-query', function (Request $request) {
        $keyword = $request->input('keyword');

        return resolve(PaczkomatyLocationRepository::class)
            ->where('city', 'LIKE', "%$keyword%")
            ->orWhere('address', 'LIKE', "%$keyword%")
            ->limit(75)
            ->get()
            ->toArray();
    })->name('shop.bagistoinpostshipping.locations_query');

    Route::get('location-selected/{location}', function (string $location) {
        $locationDetails = explode(' ', $location);

        $lockerName = str_replace(['[', ']'], '', $locationDetails[0]);

        $locker = resolve(PaczkomatyLocationRepository::class)->where('name', $location)->first();

        $shippingData = [
            'first_name' => $lockerName,
            'last_name'  => $locker->location_description,
            'address1'   => $locker->address,
            'postcode'   => $locker->post_code,
            'city'       => $locker->city,
            'state'      => $locker->province
        ];

        $cart = Cart::getCart();

        $shipping = CartAddress::findOrFail($cart->shipping_address->id);
        $shipping->update($shippingData);

        return $shippingData;
    })->name('shop.bagistoinpostshipping.location_selected');

});