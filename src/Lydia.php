<?php

namespace Pythagus\LaravelLydia;

use Pythagus\Lydia\Lydia as BaseLydia;
use Pythagus\LaravelLydia\Models\PaymentLydia;

/**
 * Class Lydia
 * @package Pythagus\LaravelLydia
 *
 * @author: Damien MOLINA
 */
class Lydia extends BaseLydia {

	/**
	 * Callable used to save the payment data.
	 *
	 * @var callable
	 */
	private static $savePaymentCallback ;

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

	/**
	 * Set the data callable.
	 *
	 * @param callable $callable
	 */
	public static function setPaymentDataCallback(callable $callable) {
		Lydia::$savePaymentCallback = $callable ;
	}

	/**
	 * Set the data callable.
	 *
	 * @param string|null $class
	 */
	public static function setDefaultPaymentDataCallback(string $class = null) {
		Lydia::setPaymentDataCallback(function(array $data) use ($class) {
			$payment = $class ? new $class : new PaymentLydia() ;
			$payment->fill($data) ;
			$payment->save() ;
		}) ;
	}

	/**
	 * Save the payment data to retrieve them
	 * to check the transaction state.
	 *
	 * @param array $data
	 */
	public function savePaymentData(array $data) {
		if(! is_null(Lydia::$savePaymentCallback)) {
			call_user_func(Lydia::$savePaymentCallback, $data) ;
		}
	}

}