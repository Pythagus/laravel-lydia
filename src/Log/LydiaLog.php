<?php

namespace Pythagus\LaravelLydia\Log;

use Closure;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Pythagus\Lydia\Contracts\LydiaException;

/**
 * Class LydiaLog
 * @package Pythagus\LaravelLydia\Log
 *
 * @property string file
 *
 * @author: Damien MOLINA
 */
class LydiaLog extends Logger {

	/**
	 * Log file name.
	 *
	 * @var string
	 */
	private $file ;

	/**
	 * Make a specific Lydia log manager.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct('lydia') ;

		$this->setFileName() ;
		$this->pushHandler(new StreamHandler(
			$this->getFilePath(), Logger::WARNING
		)) ;
	}

	/**
	 * Get the file name regarding the
	 * current date.
	 *
	 * @return void
	 */
	private function setFileName() {
		$date = date('Y-m-d') ;

		$this->file = "lydia-$date.log" ;
	}

	/**
	 * Get the file path.
	 *
	 * @return string
	 */
	public function getFilePath() {
		return storage_path('logs/lydia/' . $this->file) ;
	}

	/**
	 * Get the reportable closure for the error Handler.
	 * Useful for an application using Laravel 8.x.
	 *
	 * @return Closure
	 * @since Laravel 8.x
	 */
	public static function reportableClosure() {
		return function(LydiaException $e) {
			$logger = new LydiaLog() ;
			$logger->alert($e) ;

			return false ;
		} ;
	}

}