<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Email;
use App\User;
use App\Jobs\SendInvoiceEmail;
use Mail;
use Illuminate\Support\Facades\Auth;

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

		$receiver = User::findOrFail($request->receiver_id);

		$email = new Email;
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
		$this->sendMail($email);
		// $this->dispatch(new SendInvoiceEmail($email));
		return view('/content/email_sent');
	}

	private function sendMail(Email $email)
	{
		$invoice = $email->invoice;

		// create the pdf of the printed invoice
		$pdf = \PDF::loadView('invoice.print', compact('invoice'));
		$filename = '/tmp/invoice'.$invoice->invoice_number.'.pdf';

		// and save it somewhere
		\File::delete($filename);
		$pdf->save($filename);

		// then email the customer attaching the invoice
		// could use job queues here, but leaving as a direct send for now
		// so I don't have to worry about that listen job always running on the
		// server. Also I'll immediately know if an email didn't work
		Mail::send('emails.invoice', ['email' => $email], function ($m) use ($email, $filename) {
			$m->from($email->from, $email->sender->business_name)
			  ->to($email->to, $email->receiver->full_name)
			  ->subject($email->subject)
			  ->attach($filename);
		});

	}
}
