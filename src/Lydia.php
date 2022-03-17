<?php

namespace Pythagus\LaravelLydia;

use Pythagus\Lydia\Lydia as BaseLydia;

/**
 * Class Lydia
 * @package Pythagus\LaravelLydia
 *
 * @author: Damien MOLINA
 */
class Lydia {

	/**
	 * Get the config value, or the default one
	 * otherwise.
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function config(string $key, $default = null) {
		return config('lydia.' . $key, $default) ;
	}
}