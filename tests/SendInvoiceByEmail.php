<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Role;
use App\Email;
use Illuminate\Support\Facades\Auth;

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
        $invoice = $userAdmin->invoices->first();

        \Mail::shouldReceive('send')
            ->once()
            ->with('emails.invoice', Mockery::any(), Mockery::any());

        $this->actingAs($userAdmin)
             ->visit('/invoice/'.$invoice->id.'/email')
             ->type('cc@b.com', 'cc')
             ->type('bcc@b.com', 'bcc')
             ->type('subject', 'subject')
             ->type('body', 'body')
             ->press('btnSend');
    }
}
