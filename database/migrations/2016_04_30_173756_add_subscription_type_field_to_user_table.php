<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionTypeFieldToUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_types', function (Blueprint $table) {
			$table->increments('id');
			$table->string('description');
            $table->decimal('price',6,2);
            $table->integer('payments_per_year');
            $table->timestamps();
        });

        Schema::table('users', function ($table) {
            $table->integer('subscription_type_id')->after('postcode')->unsigned()->nullable();
            $table->foreign('subscription_type_id')->references('id')->on('subscription_types');
        });

        Artisan::call('db:seed', array('--class' => 'SubscriptionTypeTableSeeder'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropForeign('users_subscription_type_id_foreign');
            $table->dropColumn('subscription_type_id');
        });

        Schema::drop('subscription_types');
    }
}
