<?php

namespace Pythagus\LaravelLydia\Support;

use Pythagus\Lydia\Traits\LydiaTools;
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

	use LydiaTools ;

	/**
	 * Manage the incoming payment response
	 * updating the Transaction instance.
	 *
	 * @param string $payment_id
	 * @return Transaction
	 */
    protected function _manageResponse(string $payment_id) {
		/** @var PaymentLydia $payment */
		$payment     = lydia()->query('payment')->findOrFail($payment_id) ;
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
}