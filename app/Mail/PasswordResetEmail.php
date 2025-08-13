<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class PasswordResetEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;

    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Reset Password - Train4Best')
                    ->view('emails.password-reset')
                    ->with([
                        'user' => $this->user,
                        'resetUrl' => url('/reset-password/' . $this->token . '?email=' . urlencode($this->user->email))
                    ]);
    }
}