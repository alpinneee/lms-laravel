<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The payment instance.
     *
     * @var \App\Models\Payment
     */
    public $payment;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Confirmation - Train4Best',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $registration = $this->payment->registration;
        $participant = $registration->participant;
        $class = $registration->class;
        $course = $class->course;

        return new Content(
            view: 'emails.payment-confirmation',
            with: [
                'payment' => $this->payment,
                'registration' => $registration,
                'participant' => $participant,
                'class' => $class,
                'course' => $course,
                'name' => $participant->full_name,
                'status' => $this->payment->status,
                'amount' => number_format($this->payment->amount, 2),
                'date' => $this->payment->payment_date->format('d F Y'),
                'reference' => $this->payment->reference_number,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
} 