<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Email;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvoiceEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Email $email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $email = $this->email;
        $invoice = $email->invoice;

        // create the pdf of the printed invoice
        $pdf = \PDF::loadView('invoice.print', compact('invoice'));
        $filename = '/tmp/invoice'.$invoice->invoice_number.'.pdf';

        // and save it somewhere
        \File::delete($filename);
        $pdf->save($filename);

        // then email the customer attaching the invoice
        $mailer->send('emails.invoice', ['email' => $email], function ($m) use ($email, $filename) {
            $m->from($email->from, $email->sender->business_name)
              ->to($email->to, $email->receiver->full_name)
              ->subject($email->subject)
              ->attach($filename);
        });
    }
}
