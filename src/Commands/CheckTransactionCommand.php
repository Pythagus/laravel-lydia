<?php

namespace Pythagus\LaravelLydia\Commands;

use Throwable;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Pythagus\Lydia\Contracts\LydiaState;
use Illuminate\Database\Eloquent\Builder;
use Pythagus\LaravelLydia\Models\Transaction;
use Pythagus\LaravelLydia\Models\PaymentLydia;
use Pythagus\LaravelLydia\Support\ManagePaymentResponse;

/**
 * Class CheckTransactionCommand
 * @package Pythagus\LaravelLydia\Commands
 *
 * @author: Damien MOLINA
 */
abstract class CheckTransactionCommand extends Command {

	use ManagePaymentResponse ;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'check:transaction {id?}' ;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Check all the transaction in a waiting state' ;

	/**
	 * Validate a transaction.
	 *
	 * @param Transaction $transaction
	 * @return void
	 */
	abstract protected function manageValidTransaction($transaction) ;

	/**
	 * Manage a refused transaction.
	 *
	 * @param Transaction $transaction
	 * @return void
	 */
	protected function manageRefusedTransaction($transaction) {
		$transaction->state = Transaction::CANCELED ;
		$transaction->save() ;
	}

	/**
	 * Check whether a transaction is valid or not.
	 *
	 * @param Transaction $transaction
	 */
	protected function checkTransaction(Transaction $transaction) {
		$this->info('Checking transaction ' . $transaction->id) ;
		
		try {
			/** @var PaymentLydia $payment */
			$payment = $transaction->payments->sortByDesc('created_at')->first() ;

			if($payment && $payment->hasState(LydiaState::WAITING_PAYMENT)) {
				$transaction = $this->_manageResponse($payment->id) ;

				if($transaction->isConfirmed()) {
					$this->manageValidTransaction($transaction) ;
					return ;
				}
			}

			$this->manageRefusedTransaction($transaction) ;
		} catch(Throwable $throwable) {
			$this->warn("WARN : " . $throwable) ;
		}
	}

	/**
	 * The start date of checked transaction
	 * regarding their created_at date.
	 *
	 * @return Carbon
	 */
	protected function startDate() {
		return now()->subDays(2) ;
	}

	/**
	 * The end date of checked transaction
	 * regarding their created_at date.
	 *
	 * @return Carbon
	 */
	protected function endDate() {
		return now()->subDay() ;
	}

	/**
	 * Get the transactions query.
	 * 
	 * @return Builder
	 */
	protected function transactionsQuery() {
		return $this->query()
			->where('state', Transaction::WAITING)
			->where('updated_at', '>=', $this->startDate()->toDateTimeString())
			->where('updated_at', '<=', $this->endDate()->toDateTimeString()) ;
	}

	/**
	 * Create a new transaction query.
	 * 
	 * @return Builder
	 */
	protected function query() {
		return lydia()->query('transaction') ;
	}

	/**
	 * Execute the console command.
	 *
	 * @return int
	 */
	public function handle() {
		// Optional argument.
		$id = $this->argument('id') ;

		if(is_null($id)) {
			$transactions = $this->transactionsQuery()->get() ;

			/** @var Transaction $transaction */
			foreach($transactions as $transaction) {
				$this->checkTransaction($transaction) ;
			}
		} else {
			$transaction = $this->query()->find($id) ;

			if($transaction) {
				$this->checkTransaction($transaction) ;
			} else {
				$this->warn("Transaction $id not found") ;
				return Command::FAILURE ;
			}
		}

		return Command::SUCCESS ;
	}
}
