<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Invoice;

class AddInvoiceUuidFieldToInvoices extends Migration
{
    public function up()
    {
        Schema::table('invoices', function ($table) {
            $table->string('uuid')->after('id');
        });
        $invoices = Invoice::all();
        foreach($invoices as $invoice) {
            $invoice->uuid = Invoice::GenerateUUID($invoice->id);
            $invoice->save();
        }
    }

    public function down()
    {
        Schema::table('invoices', function ($table) {
            $table->dropColumn('uuid');
        });
    }
}
