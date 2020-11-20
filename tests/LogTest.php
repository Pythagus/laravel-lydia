<?php

namespace Pythagus\LaravelLydia\Tests;

use Pythagus\LaravelLydia\Log\LydiaLog;
use Pythagus\LaravelLydia\Testing\LydiaTest;

/**
 * Class LogTest
 * @package Pythagus\LaravelLydia\Tests
 *
 * @property LydiaLog logger
 *
 * @author: Damien MOLINA
 */
class LogTest extends LydiaTest {

	/**
	 * Logger instance.
	 *
	 * @var LydiaLog
	 */
	private $logger;

	/**
	 * This method is called after the
	 * Laravel application is created.
	 *
	 * @return void
	 */
	protected function setUpClass(): void {
		$this->logger = new LydiaLog() ;
	}

	/**
	 * Tests whetger the log file is created
	 * and contains something.
	 *
	 * @return void
	 */
	public function testLogCreation() {
		// The log file shouldn't exist before the tests.
		$this->assertFileDoesNotExist($file = $this->logger->getFilePath(), "File should not exist for testing the logger") ;

		$this->logger->alert("Logger test") ;

		$this->assertFileExists($file, "File hasn't been created by the logger") ;
	}

}