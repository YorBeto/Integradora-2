<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Person;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $person;
    public $activationLink;

    public function __construct($person, $activationLink)
    {
        $this->person = $person;
        $this->activationLink = $activationLink;
    }

    public function build()
    {
        return $this->subject('Activa tu cuenta')
                    ->view('emails.activation')
                    ->with([
                        'user' => $this->person->name,
                        'activationLink' => $this->activationLink
                    ]);
    }
}
