<?php

namespace Pythagus\LaravelLydia\Testing;

use Tests\TestCase;

/**
 * Class LydiaTest
 * @package Pythagus\LaravelLydia\Testing
 *
 * @author: Damien MOLINA
 */
abstract class LydiaTest extends TestCase {

	/**
	 * Create a test loading the Laravel application.
	 *
	 * @param string|null $name
	 * @param array $data
	 * @param string $dataName
	 */
	public function __construct (?string $name = null, array $data = [], $dataName = '') {
		parent::__construct($name, $data, $dataName) ;

		// Create Laravel application.
		$this->createApplication() ;

		// Set up test class.
		$this->setUpClass() ;
	}

	/**
	 * This method is called after the
	 * Laravel application is created.
	 *
	 * @return void
	 */
	protected function setUpClass(): void {
		//
	}

}