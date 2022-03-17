<?php

namespace Pythagus\LaravelLydia\Http;

use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Pythagus\LaravelLydia\Support\LydiaLog;
use Pythagus\LaravelLydia\Models\Transaction;
use Pythagus\LaravelLydia\Models\PaymentLydia;
use Pythagus\Lydia\Networking\Requests\PaymentRequest;
use Pythagus\Lydia\Networking\Requests\PaymentStateRequest;

/**
 * Class LydiaPaymentController
 * @package Pythagus\LaravelLydia\Http
 *
 * @author: Damien MOLINA
 */
abstract class LydiaPaymentController extends Controller {

    /**
     * Route called by Lydia when returning from
     * payment page.
     *
     * @var string
     */
    protected $callback_route ;

	/**
	 * Make a request to the Lydia API.
	 *
	 * @param Transaction $transaction
	 * @param string $message
	 * @return RedirectResponse
	 */
    protected function request(Transaction $transaction, string $message) {
        try {
			// Prepare the payment.
			$lydia = new PaymentLydia() ;
			$lydia->state = PaymentLydia::WAITING_PAYMENT ;
			$lydia->transaction_id = $transaction->id ;
			$lydia->save() ;

			// Make the request
			$request = new PaymentRequest() ;
			$request->setFinishCallback($this->callback_route . '/' . $lydia->id) ;
			$data = $request->execute([
				'message'   => $message,
				'recipient' => $transaction->email,
				'amount'    => $transaction->total_amount,
			]) ;

			// Save the request's data.
			$lydia->fill($data) ;
			$lydia->save() ;

			// Redirect the user
			return $request->redirect() ;
        } catch(Throwable $throwable) {
            LydiaLog::report($throwable) ;

            return $this->onRequestFail($throwable, $transaction) ;
        }
    }

    /**
     * Make something when the Lydia request call failed.
     *
     * @param Throwable $throwable
     * @param Transaction $transaction
     * @return mixed
     */
    protected function onRequestFail(Throwable $throwable, Transaction $transaction) {
        throw $throwable ;
    }

	/**
	 * Manage the incoming Lydia response.
	 *
	 * @param string $payment_id
	 * @return mixed
	 */
	protected function response(string $payment_id) {
		try {
			/** @var PaymentLydia $payment */
			$payment     = PaymentLydia::query()->findOrFail($payment_id) ;
			$transaction = $payment->transaction ;

			// Don't do anything for confirmed or displayed transaction.
			if($transaction->isConfirmed() || $transaction->displayed) {
				return $transaction ;
			}

			$transaction->displayed = true ;
			$payment->transaction_identifier = $this->getTransactionIdentifier() ;

			if(! is_null($payment->request_uuid)) {
				$request = new PaymentStateRequest($payment->request_uuid) ;
				$payment->state = $request->execute() ;
				$payment->save() ;

				if($payment->isConfirmed()) {
					$transaction->state = Transaction::CONFIRMED ;
				}
			}

			$transaction->save() ;

			return $this->onResponseSuccess($transaction) ;
		} catch(Throwable $throwable) {
			LydiaLog::report($throwable) ;

            return $this->onResponseFail($throwable, $transaction) ;
		}
	}

	/**
	 * Do something when a successfully managed Lydia response came.
	 * 
	 * @return mixed.
	 */
	protected function onResponseSuccess(Transaction $transaction) {
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

	/**
	 * We are trying to take the transaction_identifier
	 * token in the GET Lydia's response.
	 *
	 * @return string|null
	 */
	protected function getTransactionIdentifier() {
		try {
			return $_GET['transaction'] ?? null ;
		} catch(Throwable $ignored) {
			return null ;
		}
	}
}