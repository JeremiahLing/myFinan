<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $invoiceData;

    /**
     * Create a new message instance.
     */
    public function __construct($invoiceData)
    {
        $this->invoiceData = $invoiceData;
    }


    /**
     * Build the message.
     */
    public function build()
    {
        return $this
            ->subject('Your Invoice')
            ->view('emails.email-invoice')
            ->with([
                'invoiceData' => $this->invoiceData,
            ]);
    }
}
