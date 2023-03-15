<?php

use Emsit\BagistoInPostShipping\Repositories\PaczkomatyLocationRepository;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartAddress;

Route::group(['prefix' => 'bagistoinpostshipping', 'middleware' => ['web', 'theme', 'locale', 'currency']], function () {

    Route::get('locations-query/{query}', function (string $query) {
        return resolve(PaczkomatyLocationRepository::class)
            ->where('city', 'LIKE', "%$query%")
            ->orWhere('address', 'LIKE', "%$query%")
            ->limit(75)
            ->get()
            ->toArray();
    })->name('shop.bagistoinpostshipping.locations_query');

    Route::get('location-selected/{location}', function (string $location) {
        $locationDetails = explode(' ', $location);
        $cart = Cart::getCart();
        $shipping = CartAddress::findOrFail($cart->shipping_address->id);
        $shipping->first_name = $locationDetails[0];
        $shipping->address1 = $locationDetails[1] . ' ' . $locationDetails[2];
        $shipping->postcode = $locationDetails[3];
        $shipping->city = $locationDetails[4];
        $shipping->state = "chuj w dupie";
        $shipping->save();

        return $locationDetails;
    })->name('shop.bagistoinpostshipping.location_selected');

});