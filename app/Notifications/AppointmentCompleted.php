<?php

namespace App\Notifications;

use App\Models\Citas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCompleted extends Notification
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
        $esPaciente = $notifiable->email === $this->cita->paciente->email;
        
        if ($esPaciente) {
            $mailMessage = (new MailMessage)
                ->subject('✅ Su Cita Médica ha sido Completada')
                ->greeting('Estimado ' . $this->cita->paciente->nombre . ' ' . $this->cita->paciente->apellido . ',')
                ->line('Su cita fue con el médico: **' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido . '**')
                ->line('')
                ->line('📋 **INFORMACIÓN DE LA CITA COMPLETADA:**')
                ->line('')
                ->line('👨‍⚕️ **Médico:** ' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido)
                ->line('📅 **Fecha:** ' . $this->cita->fechaCita)
                ->line('🕐 **Hora:** ' . $this->cita->horaCita)
                ->line('✅ **Estado:** Completada');

            if (!empty($this->cita->observaciones)) {
                $mailMessage->line('')
                    ->line('📝 **Observaciones del Médico:**')
                    ->line($this->cita->observaciones);
            }
        } else {
            $mailMessage = (new MailMessage)
                ->subject('✅ Cita Completada')
                ->greeting('Estimado Dr. ' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido . ',')
                ->line('La cita con el paciente **' . $this->cita->paciente->nombre . ' ' . $this->cita->paciente->apellido . '** ha sido marcada como completada.')
                ->line('')
                ->line('📋 **INFORMACIÓN DE LA CITA:**')
                ->line('')
                ->line('👤 **Paciente:** ' . $this->cita->paciente->nombre . ' ' . $this->cita->paciente->apellido)
                ->line('📅 **Fecha:** ' . $this->cita->fechaCita)
                ->line('🕐 **Hora:** ' . $this->cita->horaCita)
                ->line('✅ **Estado:** Completada');

            if (!empty($this->cita->observaciones)) {
                $mailMessage->line('')
                    ->line('📝 **Observaciones:**')
                    ->line($this->cita->observaciones);
            }
        }

        return $mailMessage
            ->line('')
            ->line('🎉 **GRACIAS POR SU VISITA:**')
            ->line('• Esperamos que haya tenido una excelente experiencia')
            ->line('• Su salud es nuestra prioridad')
            ->line('• Si tiene alguna consulta posterior, no dude en contactarnos')
            ->line('• Conserve este correo como comprobante de su visita')
            ->line('')
            ->line('¡Gracias por confiar en nuestros servicios médicos!')
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
            'estado' => 'completada',
            'fecha' => $this->cita->fechaCita,
            'hora' => $this->cita->horaCita,
        ];
    }
}
