<?php

namespace App\Notifications;

use App\Models\Citas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmed extends Notification
{
    use Queueable;

    protected $cita;

    /**
     * Create a new notification instance.
     */
    public function __construct(Citas $cita)
    {
        $this->cita = $cita;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('✅ Su Cita Médica ha sido Confirmada')
            ->greeting('Estimado ' . $this->cita->paciente->nombre . ' ' . $this->cita->paciente->apellido . ',')
            ->line('Su cita está con el médico: **' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido . '**')
            ->line('')
            ->line('📋 **INFORMACIÓN DE SU CITA CONFIRMADA:**')
            ->line('')
            ->line('👨‍⚕️ **Médico:** ' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido)
            ->line('📅 **Fecha:** ' . $this->cita->fechaCita)
            ->line('🕐 **Hora:** ' . $this->cita->horaCita)
            ->line('✅ **Estado:** Confirmada');

        if (!empty($this->cita->observaciones)) {
            $mailMessage->line('')
                ->line('📝 **Observaciones del Médico:**')
                ->line($this->cita->observaciones);
        }

        return $mailMessage
            ->line('')
            ->line('📝 **INSTRUCCIONES IMPORTANTES:**')
            ->line('• Llegue **15 minutos antes** de su cita programada')
            ->line('• Traiga su documento de identidad')
            ->line('• Si toma medicamentos, traiga la lista actualizada')
            ->line('• En caso de emergencia, contacte inmediatamente')
            ->line('• Evite comer 2 horas antes si es necesario')
            ->line('')
            ->line('Estamos ansiosos por atenderle. ¡Nos vemos pronto!')
            ->line('')
            ->line('**Equipo de Citas Médicas**');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'cita_id' => $this->cita->id,
            'estado' => 'confirmada',
            'fecha' => $this->cita->fechaCita,
            'hora' => $this->cita->horaCita,
        ];
    }
}
