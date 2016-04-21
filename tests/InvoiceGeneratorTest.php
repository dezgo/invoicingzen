<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\InvoiceGenerator;

class InvoiceGeneratorTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice;

    public function create($template)
    {
        $this->invoice = factory(App\Invoice::class)->create();
        $this->be($this->invoice->user);
        $invoice_generator = new InvoiceGenerator($this->invoice, $template);
        return $invoice_generator;
    }

    public function testOutput1()
    {
        $invoice_generator = $this->create("test $*invoice_number*$ replacement");
        $this->assertTrue($invoice_generator->output() ==
            "test ".$this->invoice->invoice_number." replacement"
        );
    }

    public function testOutput2()
    {
        $invoice_generator = $this->create("test $*invoice_number*$$*invoice_number*$ replacement");
        $this->assertTrue($invoice_generator->output() ==
            "test ".$this->invoice->invoice_number.$this->invoice->invoice_number." replacement"
        );
    }

    public function testOutputStart()
    {
        $invoice_generator = $this->create("$*invoice_number*$ replacement");
        $this->assertTrue($invoice_generator->output() ==
            $this->invoice->invoice_number." replacement"
        );
    }

    public function testOutputEnd()
    {
        $invoice_generator = $this->create("test $*invoice_number*$");
        $this->assertTrue($invoice_generator->output() ==
            "test ".$this->invoice->invoice_number
        );
    }

    public function testOutputAlone()
    {
        $invoice_generator = $this->create("$*invoice_number*$");
        $this->assertTrue($invoice_generator->output() ==
            $this->invoice->invoice_number
        );
    }

    public function testOutputNothing()
    {
        $invoice_generator = $this->create("invoice_number");
        $this->assertTrue($invoice_generator->output() ==
            "invoice_number"
        );
    }

    public function testInvalidTemplate()
    {
        try {
            $invoice_generator = $this->create("test $*invoice_number replacement");
        }
        catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }
}
