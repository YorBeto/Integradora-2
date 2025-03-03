<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $frontendUrl;

    public function __construct($frontendUrl)
    {
        $this->frontendUrl = $frontendUrl;
    }

    public function build()
    {
        return $this->subject('Restablecer tu contraseña')
                    ->view('emails.password_reset')  // Vista del correo
                    ->with([
                        'frontendUrl' => $this->frontendUrl
                    ]);
    }
}
