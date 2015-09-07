<?php namespace SdhWeb\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Barryvdh\Debugbar\Facade as Debugbar;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//Extend authentication with the SDH API authentication
		Auth::extend('SdhApi', function($app)
		{
			return new \SdhWeb\Extensions\SdhApiAuthProvider();
		});

		if(isset($_ENV['APP_DEBUG_BAR']) && $_ENV['APP_DEBUG_BAR'] == 'false') {
			Debugbar::disable();
		}
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'SdhWeb\Services\Registrar'
		);
	}

}
