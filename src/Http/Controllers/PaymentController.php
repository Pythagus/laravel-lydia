<?php

namespace Pythagus\LaravelLydia\Http\Controllers;

use Throwable;
use Illuminate\Http\RedirectResponse;
use Pythagus\Lydia\Http\PaymentRequest;
use Pythagus\LaravelLydia\Models\Transaction;
use Pythagus\LaravelLydia\Models\PaymentLydia;
use Pythagus\LaravelLydia\Http\Traits\HasPaymentResponse;
use Pythagus\LaravelLydia\Exceptions\TransactionFailedException;

/**
 * Class PaymentController
 * @package Pythagus\LaravelLydia\Http\Controllers
 *
 * @author: Damien MOLINA
 */
abstract class PaymentController extends LydiaController {

    use HasPaymentResponse ;

    /**
     * Make a request to the Lydia API.
     *
     * @param Transaction $transaction
     * @param string $message
     * @return RedirectResponse
     */
    public function request(Transaction $transaction, string $message = "") {
        try {
            // Prepare the payment.
            /** @var PaymentLydia $lydia */
            $lydia = lydia()->instance('payment') ;
            $lydia->state = PaymentLydia::WAITING_PAYMENT ;
            $lydia->transaction_id = $transaction->id ;
            $lydia->save() ;

            // Make the request.
            $request = new PaymentRequest() ;
            $request->setFinishCallback($this->getPrefix() . '/lydia/' . $lydia->long_id) ;
            $data = $request->execute([
                'message'   => $message,
                'recipient' => $transaction->email,
                'amount'    => $transaction->total_amount,
            ]) ;

            // Save the request's data.
            $lydia->fill($data) ;
            $lydia->save() ;

            // Redirect the user to the Lydia website.
            return $request->redirect() ;
        } catch(Throwable $throwable) {
            return $this->onRequestFail(
                $this->manageThrowable($throwable), $transaction
            ) ;
        }
    }

    /**
     * Manage the incoming Lydia response.
     *
     * @param string $payment_id
     * @return mixed
     */
    public function response(string $payment_id) {
        try {
            $transaction = $this->manageResponse($payment_id) ;
        } catch (Throwable $throwable) {
            return $this->onResponseFail(
                $this->manageThrowable($throwable)
            ) ;
        }

        try {
            if($transaction->isCanceled()) {
                throw new TransactionFailedException($transaction) ;
            }

            return $this->onConfirmedTransaction($transaction) ;
        } catch(Throwable $throwable) {
            return $this->onResponseFail(
                $this->manageThrowable($throwable)
            ) ;
        } finally {
            $transaction->managed = true;
            $transaction->save() ;
        }
    }

    /**
     * Make something when the Lydia request call failed.
     *
     * @param Throwable $throwable
     * @param Transaction $transaction
     * @return mixed
     */
    protected function onRequestFail(Throwable $throwable, $transaction) {
        throw $throwable ;
    }

    /**
     * Do something when a successfully managed Lydia
     * Transaction returned.
     * 
     * @param Transaction $transaction
     * @return mixed.
     */
    protected function onConfirmedTransaction($transaction) {
        // 
    }

    /**
     * Do something when a successfully managed Lydia response came.
     * 
     * @return mixed.
     */
    protected function onResponseFail(Throwable $throwable) {
        throw $throwable ;
    }
}