<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnvioTokenMRP extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datos)
    {
        //
        $this->datos = $datos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->datos->remitente,$this->datos->nombre_remitente)
                    ->subject($this->datos->asunto)
                    ->view('mail.'.$this->datos->plantilla)
                    ->with([
                        'contenido_html' => $this->datos->contenido_html,
                    ]);
    }
}
