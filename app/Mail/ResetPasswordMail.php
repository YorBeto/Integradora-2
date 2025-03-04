<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $resetPasswordLink;

    public function __construct($name, $resetPasswordLink)
    {
        $this->name = $name;
        $this->resetPasswordLink = $resetPasswordLink;
    }

    public function build()
    {
        return $this->view('emails.reset_password')
            ->subject('Restablece tu Contraseña')
            ->with([
                'name' => $this->name,
                'resetPasswordLink' => $this->resetPasswordLink,
            ]);
    }
}
