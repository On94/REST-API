<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    private string $uri;

    /**
     * ForgotPasswordMail constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->uri =  route('reset.password.link',$token);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your password reset link')->markdown('emails.forgot_password')->with('uri', $this->uri);
    }
}
