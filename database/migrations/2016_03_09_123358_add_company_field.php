<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create companies table
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subdomain');
            $table->string('company_name');
            $table->timestamps();
        });

        // have to create a dummy company to avoid referential integrity errors
        // existing data needs some company anyway
        Artisan::call('db:seed', array('--class' => 'CompanyTableSeeder'));
        // $company = new App\Company;
        // $company->subdomain = 'cw';
        // $company->company_name = 'Computer Whiz - Canberra';
        // $company->save();

        // add column to users table
        Schema::table('users', function ($table) {
            $table->integer('company_id')->after('id')->unsigned();
        });

        // ensure there's data in it
        DB::table('users')->update(['company_id' => 1]);

        // now can add foreign key
        Schema::table('users', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });

        // and do the same for the other tables
        Schema::table('invoices', function ($table) {
            $table->integer('company_id')->after('id')->unsigned();
        });
        DB::table('invoices')->update(['company_id' => 1]);
        Schema::table('invoices', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::table('invoice_items', function ($table) {
            $table->integer('company_id')->after('id')->unsigned();
        });
        DB::table('invoice_items')->update(['company_id' => 1]);
        Schema::table('invoice_items', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::table('settings', function ($table) {
            $table->integer('company_id')->after('id')->unsigned();
        });
        DB::table('settings')->update(['company_id' => 1]);
        Schema::table('settings', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });

        Schema::table('invoice_item_categories', function ($table) {
            $table->integer('company_id')->after('id')->unsigned();
        });
        DB::table('invoice_item_categories')->update(['company_id' => 1]);
        Schema::table('invoice_item_categories', function ($table) {
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropForeign('users_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('invoices', function ($table) {
            $table->dropForeign('invoices_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('invoice_items', function ($table) {
            $table->dropForeign('invoice_items_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('settings', function ($table) {
            $table->dropForeign('settings_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('invoice_item_categories', function ($table) {
            $table->dropForeign('invoice_item_categories_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::drop('companies');
    }
}
