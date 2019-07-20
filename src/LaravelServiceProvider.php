<?php

namespace WebConsole\Extension;


use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Application as LaravelApplication;

//use Laravel\Lumen\Application as LumenApplication;

class LaravelServiceProvider extends  ServiceProvider
{

     /**
     * Booting the package.
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__.'/Route/routes.php');
    }


    /**
     * Register the service provider.
     */
    public function register()
    {

    }

    /**
     * Setup the config.
     */
    protected function setupConfig()
    {
        $configSource = realpath(__DIR__ . '/config.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([
                $configSource => config_path('webconsole.php')
            ]);
        }
        $this->mergeConfigFrom($configSource, 'webconsole');

    }



}
