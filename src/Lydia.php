<?php

namespace Pythagus\LaravelLydia;

use Pythagus\Lydia\Lydia as BaseLydia;

/**
 * Class Lydia
 * @package Pythagus\LaravelLydia
 *
 * @author: Damien MOLINA
 */
class Lydia extends BaseLydia {

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

}