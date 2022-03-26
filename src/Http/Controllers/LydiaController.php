<?php

namespace Pythagus\LaravelLydia\Http\Controllers;

use Throwable;
use App\Http\Controllers\Controller;
use Pythagus\LaravelLydia\Support\LydiaLog;
use Pythagus\Lydia\Exceptions\LydiaErrorResponseException;

/**
 * Class LydiaController
 * @package Pythagus\LaravelLydia\Http\Controllers
 *
 * @author: Damien MOLINA
 */
abstract class LydiaController extends Controller {

    /**
     * URL prefix used to redirect Lydia responses.
     *
     * @var string
     */
    protected $prefix ;

    /**
     * Get the prefix of the controller.
     * 
     * @return string
     */
    public function getPrefix() {
        return $this->prefix ;
    }

    /**
     * Manage the throwable and return the 
     * updated instance.
     *
     * @param Throwable $throwable
     * @return Throwable
     */
    protected function manageThrowable(Throwable $throwable) {
        LydiaLog::report($throwable) ;

        $throwable->message = $this->getThrowableMessage($throwable) ;

        return $throwable ;
    }

    /**
     * Retrieve the throwable message.
     *
     * @param Throwable $throwable
     * @return string
     */
    protected function getThrowableMessage(Throwable $throwable) {
        if($throwable instanceof LydiaErrorResponseException) {
            if($this instanceof PaymentController) {
                return trans('lydia::lydia.payment.' . $throwable->code) ;
            }
        }

        return $throwable->getMessage() ;
    }
}
