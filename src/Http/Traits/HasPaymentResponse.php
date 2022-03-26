<?php

namespace Pythagus\LaravelLydia\Http\Traits;

use Pythagus\Lydia\Traits\LydiaTools;
use Pythagus\Lydia\Http\PaymentStateRequest;
use Pythagus\LaravelLydia\Models\Transaction;
use Pythagus\LaravelLydia\Models\PaymentLydia;

/**
 * Trait HasPaymentResponse
 * @package Pythagus\LaravelLydia\Http\Traits
 * 
 * @author Damien MOLINA
 */
trait HasPaymentResponse {

	/**
	 * Manage the incoming payment response
	 * updating the Transaction instance.
	 *
	 * @param string $payment_id
	 * @return Transaction
	 */
	protected function manageResponse(string $payment_id) {
		/** @var PaymentLydia $payment */
		$payment	 = lydia()->query('payment')->where('long_id', $payment_id)->firstOrFail() ;

		/** @var Transaction $transaction */
		$transaction = $payment->transaction ;

		// Don't do anything unless the transaction is waiting.
		if($transaction->isWaiting()) {
			
			// If the payment is already confirmed.
			if($payment->isConfirmed()) {
				$transaction->state = Transaction::CONFIRMED ;

			// Else, if the payment is waiting for Lydia response and has a request_uuid.
			} else if($payment->isWaiting() && ! empty($payment->request_uuid)) {
				$payment->transaction_identifier = LydiaTools::getTransactionIdentifier() ;

				// Make a state request.
				$request = new PaymentStateRequest($payment->request_uuid) ;
				$payment->state = $request->execute() ;
				$payment->save() ;

				// Finally check the payment state.
				if($payment->isConfirmed()) {
					$transaction->state = Transaction::CONFIRMED ;
				} else {
					$transaction->state = Transaction::CANCELED ;
				}
			}

			$transaction->save() ;
		}

		return $transaction ;
	}
}