<?php

namespace App\Mail;

use App\Models\Convocatoria;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConvocatoriaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $convocatoria;

    public function __construct(Convocatoria $convocatoria)
    {
        $this->convocatoria = $convocatoria;
    }

    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject($this->convocatoria->asunto)
                    ->view('emails.convocatoria') // Esta vista la crearemos abajo
                    ->with([
                        'evento' => $this->convocatoria->evento,
                        'mensajeTexto' => $this->convocatoria->mensaje,
                    ]);
    }
}