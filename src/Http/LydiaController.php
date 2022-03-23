<?php

namespace Pythagus\LaravelLydia\Http;

use Throwable;
use App\Http\Controllers\Controller;
use Pythagus\LaravelLydia\Support\LydiaLog;
use Pythagus\Lydia\Exceptions\LydiaErrorResponseException;

/**
 * Class LydiaController
 * @package Pythagus\LaravelLydia\Http
 *
 * @author: Damien MOLINA
 */
class LydiaController extends Controller {

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
            if($this instanceof LydiaPaymentController) {
                return trans('lydia::lydia.payment.' . $throwable->code) ;
            }
        }

        return $throwable->getMessage() ;
    }
}
