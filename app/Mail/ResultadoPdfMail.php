<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Result;

class ResultadoPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $result;
    /**
     * Create a new message instance.
     */
    public function __construct(Result $result)
    {
        $this->result = $result;
    }

    public function build()
    {
        return $this->subject('Resultado de análisis')
                    ->view('emails.resultado_pdf')
                    ->attach(storage_path('app/' . $this->result->pdf_path));
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Resultado Pdf Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
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
