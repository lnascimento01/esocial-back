<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMailAlert extends Mailable
{
    use Queueable, SerializesModels;

    private $domain;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $domain)
    {
        $this->domain = $domain;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Send Expiration Domain Alert',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $domain = $this->domain['name'] . $this->domain['tld'];
        $daysToExpire = $this->domain['expiration_days'];

        return $this->html("Olá o domínio {$domain} irá expirar em {$daysToExpire} dias");
    }
}
