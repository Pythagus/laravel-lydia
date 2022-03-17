<?php

use Illuminate\Database\Schema\Blueprint;
use Pythagus\LaravelLydia\Database\AbstractLydiaTable;

/**
 * Class CreateTransactionsTable
 *
 * @author Damien MOLINA
 */
return new class extends AbstractLydiaTable {

	/**
     * Model key in the config file.
     *
     * @var string
     */
    protected $model = 'transaction' ;

    /**
     * Structure the given table.
     *
     * @param Blueprint $table
     * @return void
     */
    protected function structure(Blueprint $table) {
        $table->id() ;

        $table->string('first_name') ;
        $table->string('last_name') ;
        $table->string('email') ;
        $table->boolean('displayed')->default(false) ;
        $table->tinyInteger('state') ;
        $table->double('total_amount', 8, 2) ;

        $table->timestamps() ;
    }
} ;