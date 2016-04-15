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
    public function testBrowseToEmailLaunch()
    {
        $userAdmin = Role::where('id', 2)->first()->users->first();
        $invoice = $userAdmin->invoices->first();
        $this->actingAs($userAdmin)
             ->visit('/invoice/'.$invoice->id.'/email')
             ->see($userAdmin->email_address);
    }

    public function testSendEmail()
    {
        $userAdmin = Role::where('id', 2)->first()->users->first();
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

        $this->actingAs($userAdmin)
             ->visit('/invoice/'.$invoice->id.'/email')
             ->type('cc1@b.com', 'cc')
             ->type('bcc1@b.com', 'bcc')
             ->type('subject1', 'subject')
             ->press('btnSend');

        $mailer_result = $mailer->hasMessageFor([
                'from' => $userAdmin->email,
                'to' => $user->email,
                'cc' => 'cc1@b.com',
                'bcc' => 'bcc1@b.com',
                'subject' => 'subject1',
            ]);
        $this->assertTrue($mailer_result == 'FTCBS', $mailer_result);
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
