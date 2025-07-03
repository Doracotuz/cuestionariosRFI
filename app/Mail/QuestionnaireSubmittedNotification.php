<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\QuestionnaireResponse;

class QuestionnaireSubmittedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $questionnaireResponse;
    public $pdfContent;
    public $logoBase64; // <-- Añadido

    /**
     * Create a new message instance.
     */
    public function __construct(QuestionnaireResponse $questionnaireResponse, $pdfContent, $logoBase64 = null) // <-- Añadido $logoBase64
    {
        $this->questionnaireResponse = $questionnaireResponse;
        $this->pdfContent = $pdfContent;
        $this->logoBase64 = $logoBase64; // <-- Asignado
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cuestionario Completado: ' . ($this->questionnaireResponse->questionnaire->title ?? 'N/A'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Cambiado a una vista HTML pura para más control de estilo
        return new Content(
            view: 'emails.questionnaire-submitted-report', // <-- Apunta a la nueva vista HTML
            with: [
                'response' => $this->questionnaireResponse,
                'logoBase64' => $this->logoBase64, // <-- Pasado a la vista
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
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'reporte_cuestionario_' . $this->questionnaireResponse->id . '.pdf')
                      ->withMime('application/pdf'),
        ];
    }
}
