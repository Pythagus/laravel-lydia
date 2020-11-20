<?php

namespace Pythagus\LaravelLydia\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Pythagus\Lydia\Contracts\LydiaState;

/**
 * Class PaymentLydia
 * @package Pythagus\LaravelLydia\Models
 *
 * @property int    id
 * @property string url
 * @property string transaction_identifier
 * @property string state
 * @property int    request_id
 * @property int    request_uuid
 * @property int    transaction_id
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @author: Damien MOLINA
 */
class PaymentLydia extends Model implements LydiaState {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'payment_lydia' ;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['transaction_id', 'transaction_identifier', 'state', 'url', 'request_id', 'request_uuid'] ;

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['created_at', 'updated_at'] ;

	/**
	 * Determine whether the current payment was
	 * confirmed by Lydia.
	 *
	 * @return bool
	 */
	public function isConfirmed() {
		return $this->hasState(PaymentLydia::PAYMENT_CONFIRMED) ;
	}

	/**
	 * Determine whether the current Lydia instance
	 * has the given state.
	 *
	 * @param string $state
	 * @return bool
	 */
	public function hasState(string $state) {
		return $this->state == $state ;
	}

}