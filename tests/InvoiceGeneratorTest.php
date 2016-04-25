<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\CustomInvoice\InvoiceGenerator;
use App\Factories\SettingsFactory;

class InvoiceGeneratorTest extends TestCase
{
    use DatabaseTransactions;

    public function testOutput1()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "test $*Invoice.InvoiceNumber*$ replacement";

        $expected = "test ".$invoice->invoice_number." replacement";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutput2()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "test $*Invoice.InvoiceNumber*$$*Invoice.InvoiceNumber*$ replacement";

        $expected = "test ".$invoice->invoice_number.$invoice->invoice_number." replacement";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputStart()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "$*Invoice.InvoiceNumber*$ replacement";

        $expected = $invoice->invoice_number." replacement";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputEnd()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "test $*Invoice.InvoiceNumber*$";

        $expected = "test ".$invoice->invoice_number;
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputAlone()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "$*Invoice.InvoiceNumber*$";

        $expected = $invoice->invoice_number;
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputNothing()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "invoice_number";

        $expected = "invoice_number";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testInvalidTemplate()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "test $*Invoice.InvoiceNumber replacement";

        try {
            $actual = $invoice_generator->output($template, $invoice);
        }
        catch (\Exception $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(false);
    }

    public function testOutputCompanyLogo()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "$*Company.Logo*$";

        $expected = "<img class='left-block' src='https://localhost/images/".
            $invoice->user->company->logofilename."' />";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputCompanyName()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice->user->company->company_name = "Acme Inc";
        $invoice->user->save();
        $invoice_generator = new InvoiceGenerator();
        $template = "$*Company.Name*$";

        $expected = "Acme Inc";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputABN()
    {
        $invoice = factory(App\Invoice::class)->create();
        $this->be($invoice->user);
        $invoice_generator = new InvoiceGenerator();
        $template = "$*Settings.ABN*$";
        $settings = SettingsFactory::create();
        $settings->set('abn', '12121212');

        $expected = "12121212";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputFullname()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice->user->first_name = "Joe";
        $invoice->user->last_name = "Bloggs";
        $invoice->user->save();
        $invoice_generator = new InvoiceGenerator();
        $template = "$*User.Fullname*$";

        $expected = "Joe Bloggs";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputAddressMulti()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice->user->business_name = "Kaos Inc";
        $invoice->user->address1 = "AON Building";
        $invoice->user->address2 = "1 George St";
        $invoice->user->suburb = "Canberra";
        $invoice->user->state = "ACT";
        $invoice->user->postcode = "2600";
        $invoice->user->save();
        $invoice_generator = new InvoiceGenerator();
        $template = "$*User.AddressMulti*$";

        $expected = "Kaos Inc<br />AON Building<br />1 George St<br />Canberra ACT 2600";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputInvoiceDate()
    {
        $invoice = factory(App\Invoice::class)->create();
        $invoice_generator = new InvoiceGenerator();
        $template = "$*Invoice.InvoiceDate*$";

        $expected = $invoice->invoice_date;
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputPaymentTerms()
    {
        $invoice = factory(App\Invoice::class)->create();
        $this->be($invoice->user);
        $invoice_generator = new InvoiceGenerator();
        $template = "$*Settings.PaymentTerms*$";
        $settings = SettingsFactory::create();
        $settings->set('payment_terms', '8 Days');

        $expected = "8 Days";
        $actual = $invoice_generator->output($template, $invoice);

        $this->assertTrue($expected == $actual);
    }

    public function testOutputInvoiceItems()
    {
        $invoice = factory(App\Invoice::class)->create();
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $invoice->id]);
        $invoice_generator = new InvoiceGenerator();

        $template = "$*StartInvoiceItems*$<Tr>
            <td>$*InvoiceItem.Quantity*$</td>
            <td>$*InvoiceItem.Description*$</td>
            <td>$*InvoiceItem.Price*$</td>
        </Tr>$*EndInvoiceItems*$ and more";

        $expected = "";
        foreach ($invoice->invoice_items as $invoice_item) {

        // note: when generating expected string, tabs matter
        $expected .= "<Tr>
            <td>".$invoice_item->quantity."</td>
            <td>".$invoice_item->description."</td>
            <td>".$invoice_item->price."</td>
        </Tr>";
        }
        $expected .= ' and more';
        $actual = $invoice_generator->output($template, $invoice);
        $this->assertTrue($expected == $actual);
    }
}
