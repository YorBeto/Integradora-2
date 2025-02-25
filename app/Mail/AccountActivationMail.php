<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $activationLink;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(string $name, string $activationLink, string $password)
    {
        $this->name = $name;
        $this->activationLink = $activationLink;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Activa tu cuenta')
                    ->view('emails.activation')
                    ->with([
                        'name' => $this->name,
                        'activationLink' => $this->activationLink,
                        'password' => $this->password
                    ]);
    }
}
