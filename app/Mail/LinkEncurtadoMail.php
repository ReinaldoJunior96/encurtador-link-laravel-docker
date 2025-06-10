<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LinkEncurtadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $shortUrl;
    public $originalUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($shortUrl, $originalUrl)
    {
        $this->shortUrl = $shortUrl;
        $this->originalUrl = $originalUrl;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Obrigado pelo seu pagamento! Aqui está seu link encurtado')
            ->markdown('emails.link-encurtado')
            ->with([
                'shortUrl' => $this->shortUrl,
                'originalUrl' => $this->originalUrl,
            ]);
    }
}
