<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerify extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $code;

    /**
     * ForgotPasswordMail constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code =  $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Verify Email')->markdown('emails.email_verify')->with('code', $this->code);
    }


}
