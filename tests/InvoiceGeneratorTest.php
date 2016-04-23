<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CustomInvoice\InvoiceGenerator;

class InvoiceGeneratorTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice;

    public function create($template)
    {
        $this->invoice = factory(App\Invoice::class)->create();
        $this->be($this->invoice->user);
        $invoice_generator = new InvoiceGenerator($template);
        return $invoice_generator;
    }

    public function testOutput1()
    {
        $invoice_generator = $this->create("test $*Invoice.InvoiceNumber*$ replacement");
        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "test ".$this->invoice->invoice_number." replacement"
        );
    }

    public function testOutput2()
    {
        $invoice_generator = $this->create("test $*Invoice.InvoiceNumber*$$*Invoice.InvoiceNumber*$ replacement");
        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "test ".$this->invoice->invoice_number.$this->invoice->invoice_number." replacement"
        );
    }

    public function testOutputStart()
    {
        $invoice_generator = $this->create("$*Invoice.InvoiceNumber*$ replacement");
        $this->assertTrue($invoice_generator->output($this->invoice) ==
            $this->invoice->invoice_number." replacement"
        );
    }

    public function testOutputEnd()
    {
        $invoice_generator = $this->create("test $*Invoice.InvoiceNumber*$");
        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "test ".$this->invoice->invoice_number
        );
    }

    public function testOutputAlone()
    {
        $invoice_generator = $this->create("$*Invoice.InvoiceNumber*$");
        $this->assertTrue($invoice_generator->output($this->invoice) ==
            $this->invoice->invoice_number
        );
    }

    public function testOutputNothing()
    {
        $invoice_generator = $this->create("invoice_number");
        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "invoice_number"
        );
    }

    public function testInvalidTemplate()
    {
        try {
            $invoice_generator = $this->create("test $*Invoice.InvoiceNumber replacement");
        }
        catch (\Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    public function testOutputCompanyLogo()
    {
        $invoice_generator = $this->create("$*Company.Logo*$");
        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "<img class='left-block' src='https://localhost/images/logo.img' />"
        );
    }

    public function testOutputBusinessName()
    {
        $invoice_generator = $this->create("$*User.BusinessName*$");

        $this->invoice->user->business_name = "Acme Inc";
        $this->invoice->user->save();

        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "Acme Inc"
        );
    }
}
