<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubmissionConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $token;
    protected $doctorId;

    public function __construct($token, $doctorId)
    {
        $this->token = $token;
        $this->doctorId = $doctorId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = 'http://localhost:3000/verify-booking?token=' . $this->token . '&doctorId=' . $this->doctorId;

        return $this->markdown('emails.submission_confirmation')
            ->subject('Confirmation - Your Form Submission')
            ->with([
                'url' => $url,
            ]);
    }
}