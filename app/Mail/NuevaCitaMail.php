<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NuevaCitaMail extends Mailable
{
    use Queueable, SerializesModels;

    public Cita $cita;

    /**
     * Crear una nueva instancia del mensaje.
     */
    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    /**
     * Asunto del correo.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nueva cita veterinaria - ' . $this->cita->fecha->format('d/m/Y') . ' ' . substr($this->cita->hora, 0, 5),
        );
    }

    /**
     * Vista del correo.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.nueva-cita',
            with: [
                'cita' => $this->cita,
            ],
        );
    }

    /**
     * Archivos adjuntos.
     */
    public function attachments(): array
    {
        return [];
    }
}