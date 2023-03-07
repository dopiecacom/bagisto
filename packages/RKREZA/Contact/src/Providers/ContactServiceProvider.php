<?php

namespace RKREZA\Contact\Providers;

 use Illuminate\Support\ServiceProvider;

 class ContactServiceProvider extends ServiceProvider
 {
     /**
     * Bootstrap services.
     *
     * @return void
     */
     public function boot()
     {
          $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
          $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'contact_view');
          $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'contact_lang');
          $this->loadMigrationsFrom(__DIR__ .'/../Database/Migrations');

     }

     /**
     * Register services.
     *
     * @return void
     */
     public function register()
     {
          $this->mergeConfigFrom(dirname(__DIR__) . '/../Config/menu.php', 'menu.admin');
          $this->mergeConfigFrom(dirname(__DIR__) . '/../Config/acl.php', 'acl');
     }
 }