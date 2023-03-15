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

        $lockerName = str_replace(['[', ']'], '', $locationDetails[0]);

        $locker = resolve(PaczkomatyLocationRepository::class)->where('name', $lockerName)->first();

        $cart = Cart::getCart();
        $shipping = CartAddress::findOrFail($cart->shipping_address->id);
        $shipping->first_name = $lockerName;
        $shipping->last_name = $locker->location_description;
        $shipping->address1 = $locker->address;
        $shipping->postcode = $locker->post_code;
        $shipping->city = $locker->city;
        $shipping->state = $locker->province;
        $shipping->save();

        return $lockerName;
    })->name('shop.bagistoinpostshipping.location_selected');

});