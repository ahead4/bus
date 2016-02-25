<?php namespace Ahead4\Bus;

use Illuminate\Bus\BusServiceProvider as IlluminateBusServiceProvider;

class BusServiceProvider extends IlluminateBusServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('Illuminate\Bus\Dispatcher', function ($app) {
			return new Dispatcher($app, function () use ($app) {
				return $app['Illuminate\Contracts\Queue\Queue'];
			});
		});
		$this->app->alias(
			'Illuminate\Bus\Dispatcher',
			'Illuminate\Contracts\Bus\Dispatcher'
		);
		$this->app->alias(
			'Illuminate\Bus\Dispatcher',
			'Illuminate\Contracts\Bus\QueueingDispatcher'
		);
	}
}
