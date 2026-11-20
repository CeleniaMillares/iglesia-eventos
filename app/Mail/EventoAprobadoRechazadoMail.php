<?php

namespace App\Mail;

use App\Models\Evento;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class EventoAprobadoRechazadoMail extends Mailable
{
    use Queueable, SerializesModels;

    public bool $aprobado;

    public function __construct(
        public Evento $evento,
        ?bool $aprobado = null,
        public ?string $motivo = null
    ) {
        $this->aprobado = $aprobado ?? $evento->estado === 'aprobado';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'no-reply@iglesia.com'),
                (string) config('mail.from.name', 'Iglesia Eventos')
            ),
            subject: ($this->aprobado ? 'Evento aprobado' : 'Evento rechazado').': '.$this->evento->nombre,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.evento-aprobado-rechazado',
            with: [
                'evento' => $this->evento,
                'aprobado' => $this->aprobado,
                'motivo' => $this->motivo ?? $this->evento->motivo_rechazo,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
