<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredentialsUser extends Mailable
{
    use Queueable, SerializesModels;

    public $pass;
    public $email;
    public $names;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pass, $email, $names)
    {
        $this->pass = $pass;
        $this->email = $email;
        $this->names = $names;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SUFRAGIO SUDUNT - CREDENCIALES')->view('mails.credentials');
    }
}
