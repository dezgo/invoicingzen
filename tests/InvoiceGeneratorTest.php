<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CustomInvoice\InvoiceGenerator;
use App\Factories\SettingsFactory;

class InvoiceGeneratorTest extends TestCase
{
    use DatabaseTransactions;

    private $invoice;

    public function create($template)
    {
        $this->invoice = factory(App\Invoice::class)->create();
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
            "<img class='left-block' src='https://localhost/images/".$this->invoice->user->company->logofilename."' />"
        );
    }

    public function testOutputCompanyName()
    {
        $invoice_generator = $this->create("$*Company.Name*$");

        $this->invoice->user->company->company_name = "Acme Inc";
        $this->invoice->user->save();

        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "Acme Inc"
        );
    }

    public function testOutputABN()
    {
        $invoice_generator = $this->create("$*Settings.ABN*$");
        $this->be($this->invoice->user);

        $settings = SettingsFactory::create();
        $settings->set('abn', '12121212');

        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "12121212"
        );
    }

    public function testOutputFullname()
    {
        $invoice_generator = $this->create("$*User.Fullname*$");

        $this->invoice->user->first_name = "Joe";
        $this->invoice->user->last_name = "Bloggs";
        $this->invoice->user->save();

        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "Joe Bloggs"
        );
    }

    public function testOutputAddressMulti()
    {
        $invoice_generator = $this->create("$*User.AddressMulti*$");

        $this->invoice->user->business_name = "Kaos Inc";
        $this->invoice->user->address1 = "AON Building";
        $this->invoice->user->address2 = "1 George St";
        $this->invoice->user->suburb = "Canberra";
        $this->invoice->user->state = "ACT";
        $this->invoice->user->postcode = "2600";
        $this->invoice->user->save();

        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "Kaos Inc<br />AON Building<br />1 George St<br />Canberra ACT 2600"
        );
    }

    public function testOutputInvoiceDate()
    {
        $invoice_generator = $this->create("$*Invoice.InvoiceDate*$");
        $this->assertTrue($invoice_generator->output($this->invoice) ==
            $this->invoice->invoice_date
        );
    }

    public function testOutputPaymentTerms()
    {
        $invoice_generator = $this->create("$*Settings.PaymentTerms*$");
        $this->be($this->invoice->user);

        $settings = SettingsFactory::create();
        $settings->set('payment_terms', '8 Days');

        $this->assertTrue($invoice_generator->output($this->invoice) ==
            "8 Days"
        );
    }
}
