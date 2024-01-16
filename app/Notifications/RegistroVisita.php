<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistroVisita extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $empleado;
    public $visita;

    public function __construct($empleado, $visita)
    {
        $this->empleado = $empleado;
        $this->visita = $visita;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $dateObj = new \DateTime($this->visita->hora_ingreso);

        $mensaje = "Te acaba de llegar una visita, " . $dateObj->format('d \d\e F \d\e Y, \a \l\a\s H:i');

        $empleado = $this->empleado->nombre;
        $visitante = $this->visita->visitante_nombre;
        $cedula = $this->visita->cedula;
        $observacion = $this->visita->observacion;

        $appUrl = config('app.storage_url');
        $imagen = $appUrl.'/'.'visitas/' . $this->visita->visitante_foto;

        return (new MailMessage)
            ->subject("Tienes una nueva visita")
            ->view('emails.visitas.registro-visita', compact('mensaje','visitante','empleado','imagen','cedula','observacion'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
