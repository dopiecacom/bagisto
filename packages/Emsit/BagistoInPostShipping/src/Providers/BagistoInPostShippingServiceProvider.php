<?php

namespace Emsit\BagistoInPostShipping\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartAddress;

class BagistoInPostShippingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadRoutesFrom(__DIR__ . '/../routes.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'bagistoinpostshipping');

        $this->composeView();

        Event::listen('bagisto.shop.checkout.shipping-method.after', function($viewRenderEventManager) {
            if ($viewRenderEventManager->getParam('rateGroup')['rates'][0]->getAttribute('carrier') === "bagistoinpostshipping") {
                $viewRenderEventManager->addTemplate('bagistoinpostshipping::shop.dropdown');
            }
        });

    }

    /**
     * Bind the the data to the views.
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer('bagistoinpostshipping::shop.dropdown', function ($view) {
            $lockers = resolve(\Emsit\BagistoInPostShipping\Repositories\PaczkomatyLocationRepository::class)->limit(75);

            $view->with('lockers', $lockers);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/carriers.php', 'carriers'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}
