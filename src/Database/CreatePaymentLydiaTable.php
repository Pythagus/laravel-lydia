<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Pythagus\LaravelLydia\Models\PaymentLydia;

/**
 * Class CreatePaymentLydiaTable
 *
 * @author: Damien MOLINA
 */
class CreatePaymentLydiaTable extends Migration {

	/**
	 * Get the model table name.
	 *
	 * @return string
	 */
	private function getTableName() {
		return app(PaymentLydia::class)->getTable() ;
	}


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create($this->getTableName(), function (Blueprint $table) {
			$table->id();

			$table->unsignedBigInteger('transaction_id') ;
			$table->tinyInteger('state') ;
			$table->string('url')->nullable() ;
			$table->string('transaction_identifier')->nullable() ;
			$table->string('request_id')->nullable() ;
			$table->string('request_uuid')->nullable() ;

			$table->timestamps() ;

			// Should not delete on cascade to preserve the payment.
			// $table->foreign('transaction_id')->references('id')->on('transactions') ;
		}) ;
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists($this->getTableName()) ;
	}

}