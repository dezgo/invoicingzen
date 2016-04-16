<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Role;
use App\Email;
use Illuminate\Support\Facades\Auth;
use App\Invoice;

class SendInvoiceByEmail extends TestCase
{
    private $userAdmin;
    private $invoice;

    public function setUp()
    {
        parent::setUp();

        $this->userAdmin = Role::where('id', 2)->first()->users->first();
        $this->invoice = $this->userAdmin->invoices->first();

        // now put in mail settings as userAdmin
        $this->be($this->userAdmin);
        $settings = \App::make('App\Contracts\Settings');
        $settings->set('email_host', env('MAIL_HOST'));
        $settings->set('email_port', env('MAIL_PORT'));
        $settings->set('email_username', env('MAIL_USERNAME'));
        $settings->set('email_password', env('MAIL_PASSWORD'));
        $settings->set('email_encryption', env('MAIL_ENCRYPTION'));
    }

    public function testBlankEmailSettings()
    {
        $this->be($this->userAdmin);
        $settings = \App::make('App\Contracts\Settings');
        $host = $settings->get('email_host');
        $settings->set('email_host', '');
        $this->actingAs($this->userAdmin)
             ->visit('/invoice/'.$this->invoice->id.'/email')
             ->see(trans('invoice_email.warning-empty-settings'));

        $settings->set('email_host', $host);
    }

    public function testBrowseToEmailLaunch()
    {
        $this->actingAs($this->userAdmin)
             ->visit('/invoice/'.$this->invoice->id.'/email')
             ->see($this->userAdmin->email_address);
    }

    public function testSendEmail()
    {
        $invoices = Invoice::all();
        foreach ($invoices as $invoice) {
            if ($invoice->user->isUser()) {
                $user = $invoice->user;
                continue;
            }
        }

        $mailer = new InMemoryInbox;
        $this->app->instance('mailer', $mailer);

        // $this->pdf = \PDF::loadView('invoice.print', compact('invoice', 'settings'));

        $pdf = Mockery::spy();
        \PDF::shouldReceive('loadView')->with('invoice.print', Mockery::any())->andReturn($pdf);

        $this->actingAs($this->userAdmin)
             ->visit('/invoice/'.$invoice->id.'/email')
             ->type('cc1@b.com', 'cc')
             ->type('bcc1@b.com', 'bcc')
             ->type('subject1', 'subject')
             ->press('btnSend');

        $mailer_result = $mailer->hasMessageFor([
                'from' => $this->userAdmin->email,
                'to' => $user->email,
                'cc' => 'cc1@b.com',
                'bcc' => 'bcc1@b.com',
                'subject' => 'subject1',
            ]);
        $this->assertTrue($mailer_result == 'FTCBS', $mailer_result);
    }

    public function testSendEmailReal()
    {
        factory(App\InvoiceItem::class, 5)->create(['invoice_id' => $this->invoice->id]);

        $this->actingAs($this->userAdmin)
            ->visit('/invoice/'.$this->invoice->id.'/email')
            ->press('btnSend')
            ->see('Email sent');
    }
}

class InMemoryInbox
{
    private $messages;

    public function __construct()
    {
        $this->messages = collect();
    }

    public function send($template, $data, $callback)
    {
        $message = new Message($template, $data);
        $callback($message);
        $this->messages[] = $message;
    }

    public function hasMessageFor($email)
    {
        $message_found = array_filter(
            $this->messages->toArray(),
            function ($message) use ($email) {
                return $message->to == $email['to'];
            }
        );
        if ($message_found == null) {
            return 'ftcbs';
        }
        else {
            return ($message_found[0]->from == $email['from'] ? 'F' : 'f').
               ($message_found[0]->to == $email['to'] ? 'T' : 't').
               ($message_found[0]->cc == $email['cc'] ? 'C' : 'c').
               ($message_found[0]->bcc == $email['bcc'] ? 'B' : 'b').
               ($message_found[0]->subject == $email['subject'] ? 'S' : 's');
           }
       }
}

class Message
{
    public $template;
    public $data;
    public $from;
    public $to;
    public $cc;
    public $bcc;
    public $subject;
    public $filename;

    public function __construct($template, $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function cc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

    public function bcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function attach($filename)
    {
        $this->filename = $filename;
        return $this;
    }
}
