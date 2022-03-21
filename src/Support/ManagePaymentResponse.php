<?php

namespace Pythagus\LaravelLydia\Support;

use Throwable;
use Pythagus\LaravelLydia\Models\Transaction;
use Pythagus\LaravelLydia\Models\PaymentLydia;
use Pythagus\Lydia\Networking\Requests\PaymentStateRequest;

/**
 * Trait ManagePaymentResponse
 * @package Pythagus\LaravelLydia\Support
 * 
 * @author Damien MOLINA
 */
trait ManagePaymentResponse {

	/**
	 * Manage the incoming payment response
	 * updating the Transaction instance.
	 *
	 * @param string $payment_id
	 * @return Transaction
	 */
    protected function _manageResponse(string $payment_id) {
		/** @var PaymentLydia $payment */
		$payment     = PaymentLydia::query()->findOrFail($payment_id) ;
		$transaction = $payment->transaction ;

		// Don't do anything for confirmed or displayed transaction.
		if($transaction->isConfirmed() || (! $transaction->isWaiting() && $transaction->displayed)) {
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

		return $transaction ;
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