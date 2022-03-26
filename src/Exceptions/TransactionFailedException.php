<?php

namespace Pythagus\LaravelLydia\Exceptions;

use Pythagus\Lydia\Contracts\LydiaException;
use Pythagus\LaravelLydia\Models\Transaction;

/**
 * Class TransactionFailedException
 * @package Pythagus\LaravelLydia\Exceptions
 *
 * @author: Damien MOLINA
 */
class TransactionFailedException extends LydiaException {

    /**
     * Transaction that failed.
     *
     * @var Transaction
     */
    public $transaction = null ;

    /**
     * Exception built when a transaction failed.
     *
     * @param Transaction $transaction
     * @param string $message
     */
    public function __construct(Transaction $transaction = null, $message = "Transaction failed") {
        parent::__construct($message) ;

        $this->transaction = $transaction ;
    }
}
