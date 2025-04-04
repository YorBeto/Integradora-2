<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $newPassword;

    public function __construct($userName, $newPassword)
    {
        $this->userName = $userName;
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('Tu nueva contraseña')
                    ->view('emails.reset-password')
                    ->with([
                        'name' => $this->userName,  // Pasamos el nombre aquí
                        'newPassword' => $this->newPassword,
                    ]);
    }
}
