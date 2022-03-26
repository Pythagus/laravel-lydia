<?php

namespace Pythagus\LaravelLydia\Exceptions;

use Pythagus\Lydia\Contracts\LydiaException;

/**
 * Class TransactionFailedException
 * @package Pythagus\LaravelLydia\Exceptions
 *
 * @author: Damien MOLINA
 */
class TransactionFailedException extends LydiaException {

    /**
     * Exception built when a transaction failed.
     *
     * @param string $message
     */
    public function __construct($message = "Transaction failed") {
        parent::__construct($message) ;
    }
}
