<?php

namespace Pythagus\LaravelLydia\Http;

use Illuminate\Support\Facades\Route as RoutingFacade;

/**
 * Class Route
 * @package Pythagus\LaravelLydia\Http
 *
 * @author: Damien MOLINA
 */
class Route {

    /**
     * Controller name.
     *
     * @var string
     */
    private $controller ;

    /**
     * Route prefix.
     *
     * @var string
     */
    private $prefix ;

    /**
     * Make a new route instance.
     *
     * @param string $controller
     * @param string|null $prefix
     */
    public function __construct(string $controller, string $prefix = null) {
        $this->controller = $controller ;
        $this->prefix     = $prefix ;
    }

    /**
     * Set up a new Lydia route.
     *
     * @param string $method
     * @param string $uri
     * @param string|array $methods
     * @return void
     */
    private function setupRoute(string $controllerMethod, string $uri, $methods = ['get', 'post']) {
        RoutingFacade::match(
            $methods, ($this->prefix ? $this->prefix . '/' : '') . $uri, $this->controller . '@' . $controllerMethod
        ) ;
    }

    /**
     * Set up payment routes.
     *
     * @return void
     */
    public function payments(array $options = []) {
        if($options['response'] ?? true) {
            $this->setupRoute('response', 'lydia/{payment_id}') ;
        }

        if($options['display'] ?? true) {
            $this->setupRoute('display', 'transaction/{long_id}', 'get') ;
        }
    }
}