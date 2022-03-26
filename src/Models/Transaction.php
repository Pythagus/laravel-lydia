<?php

namespace Pythagus\LaravelLydia\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Pythagus\LaravelLydia\Support\HasState;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Pythagus\LaravelLydia\Support\HasLongIdentifier;

/**
 * Class Transaction
 * @package Pythagus\LaravelLydia\Models
 *
 * @property int    id
 * @property string long_id
 * @property string first_name
 * @property string last_name
 * @property string email
 * @property bool   managed
 * @property int    state
 * @property float  total_amount
 *
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Collection billets
 * @property Collection payments
 *
 * @author: Damien MOLINA
 */
class Transaction extends Model {

    use HasState, HasLongIdentifier ;

    /**
     * The transaction is waiting for a response or cancelled
     * by the user.
     *
     * @const int
     */
    public const WAITING = 0 ;

    /**
     * The transaction is confirmed.
     *
     * @const int
     */
    public const CONFIRMED = 1 ;

    /**
     * The transaction was canceled.
     *
     * @const int
     */
    public const CANCELED = 2 ;

    /**
     * The transaction was refunded.
     *
     * @const int
     */
    public const REFUND = 3 ;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions' ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'state', 'total_amount'
    ] ;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ] ;

    /**
     * Get the payment instances.
     *
     * @return HasMany
     */
    public function payments() {
        return $this->hasMany(
            lydia()->config('models.payment'), 'transaction_id'
        ) ;
    }

    /**
     * Determine whether the transaction is waiting.
     *
     * @return bool
     */
    public function isWaiting() {
        return $this->hasState(Transaction::WAITING) ;
    }

    /**
     * Determine whether the transaction is confirmed.
     *
     * @return bool
     */
    public function isConfirmed() {
        return $this->hasState(Transaction::CONFIRMED) ;
    }

    /**
     * Determine whether the transaction is canceled.
     *
     * @return bool
     */
    public function isCanceled() {
        return $this->hasState(Transaction::CANCELED) ;
    }

    /**
     * Determine whether the transaction is refunded.
     *
     * @return bool
     */
    public function isRefunded() {
        return $this->hasState(Transaction::REFUND) ;
    }

    /**
     * Format the created date for human.
     *
     * @return string
     */
    public function formatCreatedDate(string $format = 'Y-m-d H:i') {
        return Carbon::parse($this->created_at)->format($format) ;
    }
}
