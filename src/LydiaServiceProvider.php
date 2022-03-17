<?php

namespace Pythagus\LaravelLydia;

use Pythagus\Lydia\Lydia as OldLydia;
use Illuminate\Support\ServiceProvider;

/**
 * Class LydiaServiceProvider
 * @package Pythagus\LaravelLydia
 *
 * @author: Damien MOLINA
 */
class LydiaServiceProvider extends ServiceProvider {
	/**
	 * Package slug in the Laravel artisan tool.
	 *
	 * @const string
	 */
	private const PACKAGE_SLUG = 'lydia' ;

	/**
	 * Default config file.
	 *
	 * @const string
	 */
	private const CONFIG_FILE = __DIR__ . '/config/lydia.php' ;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->mergeConfigFrom(
			LydiaServiceProvider::CONFIG_FILE, $this->packageKey()
		) ;

		OldLydia::setInstance(new class extends OldLydia {
			/**
			 * Get the Lydia's configuration.
			 *
			 * @return array
			 */
			protected function setConfigArray() {
				return config('lydia') ;
			}

			/**
			 * Format the callback URL to be valid
			 * regarding the Lydia server.
			 *
			 * @param string $url
			 * @return string
			 */
			public function formatCallbackUrl(string $url) {
				return url('/') . '/' . $url ;
			}

			/**
			 * Redirect the user to the given route.
			 *
			 * @param string $route
			 * @return mixed
			 */
			public function redirect(string $route) {
				return redirect($route) ;
			}
		}) ;
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		// Publish the config file.
		$this->publish(
			LydiaServiceProvider::CONFIG_FILE, config_path($this->packageKey().'.php'), 'config'
		) ;

		// Publish the migration file.
		$time = time() ;
		$this->publishDatabase('CreateTransactionsTable', 'create_transactions_table', $time) ;
		$this->publishDatabase('CreatePaymentLydiaTable', 'create_payment_lydia_table', $time + 1) ;
	}

	/**
	 * Publish the datatables files.
	 *
	 * @param string $file_name
	 * @param string $destination
	 * @return void
	 */
	private function publishDatabase(string $file_name, string $destination, int $time) {
		$this->publish(__DIR__ . "/Database/$file_name.php",
			database_path('migrations/' . date('Y_m_d_His', $time) . "_$destination.php"), 'migration'
		) ;
	}

	/**
	 * Get the package key for the Laravel artisan.
	 *
	 * @param string|null $key
	 * @return string
	 */
	private function packageKey(string $key = null) {
		return LydiaServiceProvider::PACKAGE_SLUG . (is_null($key) ? "" : '-' . $key) ;
	}

	/**
	 * Publish the given file.
	 *
	 * @param string $file
	 * @param string $destination
	 * @param string|null $group
	 */
	private function publish(string $file, string $destination, string $group = null) {
		$this->publishes([$file => $destination], $group ? $this->packageKey($group) : null) ;
	}
}