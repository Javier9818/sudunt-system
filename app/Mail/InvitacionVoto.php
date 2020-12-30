<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitacionVoto extends Mailable
{
    use Queueable, SerializesModels;

    public $empadronado;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($empadronado)
    {
        $this->empadronado = $empadronado;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SISTEMA DE SUFRAGIO SUDUNT - ACTO DE SUFRAGIO')->view('mails.invitacionVoto');
    }
}
