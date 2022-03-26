<?php

namespace Pythagus\LaravelLydia;

use ReflectionClass;
use Pythagus\LaravelLydia\Http\Route;
use Illuminate\Database\Eloquent\Builder;
use Pythagus\LaravelLydia\Http\LydiaController;

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
        return $this->config('enabled', false) ;
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
        // If the given controller is a ::class.
        if(class_exists($controller)) {
            /** @var LydiaController $instance */
            $instance = new $controller ;

            return new Route(
                (new ReflectionClass($instance))->getShortName(), $prefix ?? $instance->getPrefix()
            ) ;
        }

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