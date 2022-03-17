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
     * @return void
     */
    private function setupRoute(string $method, string $uri) {
        RoutingFacade::match(['get', 'post'], ($this->prefix ? $this->prefix . '/' : '') . $uri, $this->controller . '@' . $method) ;
    }

    /**
     * Set up payment routes.
     *
     * @return void
     */
    public function payments() {
        $this->setupRoute('response', 'lydia/{payment_id}') ;
    }
}