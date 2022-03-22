<?php

namespace Pythagus\LaravelLydia\Http;

use Throwable;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Pythagus\LaravelLydia\Support\LydiaLog;
use Pythagus\LaravelLydia\Models\Transaction;
use Pythagus\LaravelLydia\Models\PaymentLydia;
use Pythagus\Lydia\Networking\Requests\PaymentRequest;
use Pythagus\LaravelLydia\Support\ManagePaymentResponse;

/**
 * Class LydiaPaymentController
 * @package Pythagus\LaravelLydia\Http
 *
 * @author: Damien MOLINA
 */
abstract class LydiaPaymentController extends Controller {

	use ManagePaymentResponse ;

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
			return $this->onResponseSuccess(
				$this->_manageResponse($payment_id)
			) ;
		} catch(Throwable $throwable) {
			LydiaLog::report($throwable) ;

			return $this->onResponseFail($throwable) ;
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
}