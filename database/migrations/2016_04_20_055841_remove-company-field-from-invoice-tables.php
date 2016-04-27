<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCompanyFieldFromInvoiceTables extends Migration
{
    public function up()
    {
        Schema::table('invoices', function ($table) {
            $table->dropForeign('invoices_company_id_foreign');
            $table->dropColumn('company_id');
        });

        Schema::table('invoice_items', function ($table) {
            $table->dropForeign('invoice_items_company_id_foreign');
            $table->dropColumn('company_id');
        });

    }

    public function down()
    {
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

    }
}
