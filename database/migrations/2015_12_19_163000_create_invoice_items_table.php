<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_items', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('invoice_id')->unsigned();
			$table->foreign('invoice_id')->references('id')->on('invoices');
			$table->integer('category_id')->unsigned();
			$table->foreign('category_id')->references('id')->on('invoice_item_categories');
			$table->string('description');
			$table->integer('quantity')->unsigned();
			$table->decimal('price',6,2);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoice_items');
	}
}
