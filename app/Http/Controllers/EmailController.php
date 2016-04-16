<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Email;
use App\User;
use App\Jobs\SendInvoiceEmail;
use Mail;
use App\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Config\Repository as Config;
use App\Services\PDFFileInvoiceGenerator;

class EmailController extends Controller
{
	/**
	 * Send an email
	 */
	public function send(Request $request)
	{
		$this->validate($request, [
	        'cc' => 'email',
	        'bcc' => 'email',
			'subject' => 'required',
			'body' => 'required'
	    ]);

		$email = $this->createEmailObject($request, Auth::user());

		$this->setMailParameters();

		$this->sendEmail($email);

		return view('/content/email_sent');
	}

	private function createEmailObject(Request $request, User $user)
	{
		$receiver = User::findOrFail($request->receiver_id);

		$email = new Email();
		$email->cc = $request->cc;
		$email->bcc = $request->bcc;
		$email->subject = $request->subject;
		$email->body = $request->body;
		$email->sender_id = $user->id;
		$email->receiver_id = $receiver->id;
		$email->invoice_id = $request->invoice_id;
		$email->from = $user->email;
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
		// dd($email);
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
			$m->attach($this->createPDF($email->invoice));
		});
	}

	private function createPDF(Invoice $invoice)
	{
		$pdf = new PDFFileInvoiceGenerator();
		$pdf->create($invoice);

		return $pdf->output();
	}
}
