<?php

namespace swede2k\XF2Bridge;

class XF2BridgeServiceProvider extends \Illuminate\Support\ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ .'/../config/xf2bridge.php', 'xf2bridge'
        );

        $this->app->singleton(XF2Bridge::class, function($app) {
            //Set Bridge loaded to true
            $app['XF2Bridge.loaded'] = true;

            $xenforoDir = config('xf2bridge.xenforo_directory_path');
            $xenforoBaseUrl = config('xf2bridge.xenforo_base_url_path');

            return new XF2Bridge($xenforoDir, $xenforoBaseUrl);
        });
        $this->app->alias(XF2Bridge::class, 'xf2bridge');
    }
        
    public function boot()
    {
        $this->publishes([
            __DIR__ .'/../config/xf2bridge.php' => config_path('xf2bridge.php'),
        ]);

    	if(config('xf2bridge.use_xenforo_auth') === true)
        {
           \Auth::extend('xf2bridge',function($app, $name, $config) {
                return new XF2Guard($app->make(XF2Bridge::class), $config['provider']);
            });
        }
    }

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('xf2bridge', XF2Bridge::class);
	}

}
