<?php

namespace Pythagus\LaravelLydia;

use Pythagus\LaravelLydia\Http\Route;
use Illuminate\Database\Eloquent\Builder;

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

	/**
	 * Create a new model instance.
	 *
	 * @param string $config_key
	 * @return object|null
	 */
	public function instance(string $config_key) {
		$class = $this->config('models.' . $config_key) ;

		if($class) {
			return new $class ;
		}

		return null ;
	}

	/**
	 * Create a new routing instance.
	 *
	 * @param string $controller
	 * @param string|null $prefix
	 * @return Route
	 */
	public function routes(string $controller, string $prefix = null) {
		return new Route($controller, $prefix) ;
	}

	/**
	 * Create a new query instance for the
	 * given model name.
	 *
	 * @param string $model
	 * @return Builder|null
	 */
	public function query(string $model) {
		$class = $this->config('models.' . $model) ;

		if($class) {
			return $class::query() ;
		}

		return null ;
	}
}