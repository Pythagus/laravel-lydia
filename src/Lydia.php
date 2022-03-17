<?php

namespace Pythagus\LaravelLydia;

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

	/**
	 * Determine whether Lydia is enabled.
	 *
	 * @return bool
	 */
	public function enabled() {
		return $this->config('enabled') ;
	}
}