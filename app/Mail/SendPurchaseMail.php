<?php

namespace Crater\Mail;

use Crater\Models\EmailLog;
use Crater\Models\Purchase;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPurchaseMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $data = [];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        EmailLog::create([
            'from' => $this->data['from'],
            'to' => $this->data['to'],
            'subject' => $this->data['subject'],
            'body' => $this->data['body'],
            'mailable_type' => Purchase::class,
            'mailable_id' => $this->data['purchase']['id'],
        ]);

        $mailContent = $this->from($this->data['from'], config('mail.from.name'))
            ->subject($this->data['subject'])
            ->markdown('emails.send.purchase', ['data', $this->data]);

        if ($this->data['attach']['data']) {
            $mailContent->attachData(
                $this->data['attach']['data']->output(),
                $this->data['purchase']['purchase_no'].'.pdf'
            );
        }

        return $mailContent;
    }
}
