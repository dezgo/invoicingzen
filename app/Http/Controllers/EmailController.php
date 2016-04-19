<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Email;
use App\User;
use Mail;
use App\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Config\Repository as Config;
use Gate;

class EmailController extends Controller
{
	public function showComposeEmailView(Invoice $invoice)
	{
		if (Gate::denies('view-invoice', $invoice)) {
			abort(403);
		}

		$settings = \App::make('App\Contracts\Settings');
		if ($settings->checkEmailSettings()) {
			$email = $this->setupEmailObjectPreEmailView($invoice);
			return view('invoice.email', compact('email'));
		}
		else {
			\Session()->flash('status-warning', trans('invoice_email.warning-empty-settings'));
			return redirect('/invoice/'.$invoice->id);
		}
	}

	private function setupEmailObjectPreEmailView(Invoice $invoice)
	{
		$email = new Email();
		$email->to = $invoice->user->email;
		$email->receiver_id = $invoice->user->id;
		$email->invoice_id = $invoice->id;
		$email->subject = $this->subject($invoice);
		$email->invoice = $this;
		$email->body = $this->body($invoice);
		return $email;
	}

	// the default subject for invoice emails
    private function subject($invoice)
    {
        return 'Invoice '.$invoice->invoice_number;
    }

    // the default body text for invoice emails
    private function body($invoice)
    {
		$settings = \App::make('App\Contracts\Settings');
        return
			'Hi '.$invoice->user->first_name.',<br />'.
			'<br />'.
			'Click the link below to view invoice '.$invoice->invoice_number.' for $'.
			number_format($invoice->total, 2).'<br />'.
            '<a href=\''.url('/view/'.$invoice->uuid).'\'>View Invoice</a><br />'.
			'<br />'.
			'Or copy and paste the following into your web browser<br />'.
			url('/view/'.$invoice->uuid).'<br />'.
			'<br />'.
			'Thanks,<br />'.
			Auth::user()->name.'<br />'.
			$settings->get('email_signature');
    }

	public function send(Request $request)
	{
		$this->validate($request, [
	        'cc' => 'email',
	        'bcc' => 'email',
			'subject' => 'required',
			'body' => 'required'
	    ]);

		$email = $this->setupEmailObjectPostEmailView($request);

		$this->setMailParameters();

		$this->sendEmail($email);

		return view('/content/email_sent');
	}

	private function setupEmailObjectPostEmailView(Request $request)
	{
		$receiver = User::findOrFail($request->receiver_id);

		$email = new Email();
		$email->cc = $request->cc;
		$email->bcc = $request->bcc;
		$email->subject = $request->subject;
		$email->body = $request->body;
		$email->sender_id = Auth::user()->id;
		$email->receiver_id = $receiver->id;
		$email->invoice_id = $request->invoice_id;
		$email->from = Auth::user()->email;
		$email->to = $receiver->email;
		$email->save();
		return $email;
	}

	private function setMailParameters()
	{
		$settings = \App::make('App\Contracts\Settings');
		app()->config['mail.host'] = $settings->get('email_host');
		app()->config['mail.port'] = $settings->get('email_port');
		app()->config['mail.username'] = $settings->get('email_username');
		app()->config['mail.password'] = $settings->get('email_password');
		app()->config['mail.encryption'] = $settings->get('email_encryption');
	}

	private function sendEmail(Email $email)
	{
		// then email the customer attaching the invoice
		// could use job queues here, but leaving as a direct send for now
		// so I don't have to worry about that listen job always running on the
		// server. Also I'll immediately know if an email didn't work
		Mail::send('emails.invoice', ['email' => $email], function ($m) use ($email) {
			$m->from($email->from, $email->sender->business_name);
			$m->to($email->to, $email->receiver->full_name);
			if ($email->cc != '') {
				$m->cc($email->cc);
			}
			if ($email->bcc != '') {
				$m->bcc($email->bcc);
			}
			$m->subject($email->subject);
		});
	}
}
