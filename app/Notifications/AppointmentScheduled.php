<?php

namespace App\Notifications;

use App\Models\Citas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentScheduled extends Notification
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
        // Determinar si el destinatario es el paciente o el médico
        $esPaciente = $notifiable->email === $this->cita->paciente->email;
        
        if ($esPaciente) {
            // Email para el paciente
            $mailMessage = (new MailMessage)
                ->subject('📅 Su Cita Médica ha sido Programada')
                ->greeting('Estimado ' . $this->cita->paciente->nombre . ' ' . $this->cita->paciente->apellido . ',')
                ->line('Su cita está con el médico: **' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido . '**')
                ->line('')
                ->line('📋 **INFORMACIÓN DE SU CITA:**')
                ->line('')
                ->line('👨‍⚕️ **Médico:** ' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido)
                ->line('📅 **Fecha:** ' . $this->cita->fechaCita)
                ->line('🕐 **Hora:** ' . $this->cita->horaCita)
                ->line('📊 **Estado:** Pendiente de Confirmación');

            if (!empty($this->cita->observaciones)) {
                $mailMessage->line('')
                    ->line('📝 **Observaciones del Médico:**')
                    ->line($this->cita->observaciones);
            }
        } else {
            // Email para el médico
            $mailMessage = (new MailMessage)
                ->subject('📅 Nueva Cita Programada')
                ->greeting('Estimado Dr. ' . $this->cita->medico->nombre . ' ' . $this->cita->medico->apellido . ',')
                ->line('Tiene una nueva cita programada con el paciente: **' . $this->cita->paciente->nombre . ' ' . $this->cita->paciente->apellido . '**')
                ->line('')
                ->line('📋 **INFORMACIÓN DE LA CITA:**')
                ->line('')
                ->line('👤 **Paciente:** ' . $this->cita->paciente->nombre . ' ' . $this->cita->paciente->apellido)
                ->line('📧 **Email:** ' . $this->cita->paciente->email)
                ->line('📱 **Teléfono:** ' . $this->cita->paciente->telefono)
                ->line('📅 **Fecha:** ' . $this->cita->fechaCita)
                ->line('🕐 **Hora:** ' . $this->cita->horaCita)
                ->line('📊 **Estado:** Pendiente de Confirmación');

            if (!empty($this->cita->observaciones)) {
                $mailMessage->line('')
                    ->line('📝 **Observaciones:**')
                    ->line($this->cita->observaciones);
            }
        }

        return $mailMessage
            ->line('')
            ->line('⚠️ **IMPORTANTE:**')
            ->line('• Por favor, confirme su asistencia lo antes posible')
            ->line('• Llegue 15 minutos antes de su cita programada')
            ->line('• En caso de no poder asistir, cancele con anticipación')
            ->line('• Traiga su documento de identidad')
            ->line('')
            ->line('Si tiene alguna pregunta o necesita reprogramar su cita, no dude en contactarnos.')
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
            'estado' => 'pendiente',
            'fecha' => $this->cita->fechaCita,
            'hora' => $this->cita->horaCita,
        ];
    }
}
