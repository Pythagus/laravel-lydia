<?php

use Illuminate\Database\Schema\Blueprint;
use Pythagus\LaravelLydia\Database\AbstractLydiaTable;

return new class extends AbstractLydiaTable {

	/**
     * Model key in the config file.
     *
     * @var string
     */
    protected $model = 'payment' ;

	/**
     * Structure the given table.
     *
     * @param Blueprint $table
     * @return void
     */
    protected function structure(Blueprint $table) {
		$table->id() ;
		$this->longIdColumn($table) ;
		
		$table->unsignedBigInteger('transaction_id') ;
		$table->tinyInteger('state') ;
		$table->string('url')->nullable() ;
		$table->string('transaction_identifier')->nullable() ;
		$table->string('request_id')->nullable() ;
		$table->string('request_uuid')->nullable() ;
		$table->timestamps() ;

		// Should not delete on cascade to preserve the payment.
		$table->foreign('transaction_id')->references('id')->on(
			$this->tableNameModel('transaction')
		)->restrictOnDelete() ;
	}
} ;