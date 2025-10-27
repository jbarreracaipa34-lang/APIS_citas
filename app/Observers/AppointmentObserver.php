<?php

namespace App\Observers;

use App\Models\Citas;
use App\Notifications\AppointmentScheduled;
use App\Notifications\AppointmentConfirmed;
use App\Notifications\AppointmentCancelled;
use App\Notifications\AppointmentCompleted;

class AppointmentObserver
{
    /**
     * Handle the Citas "created" event.
     */
    public function created(Citas $citas): void
    {
        if ($citas->estado === 'pendiente') {
            // Enviar notificación al paciente
            $citas->paciente->notify(new AppointmentScheduled($citas));
            
            // Enviar notificación al médico
            if ($citas->medico && $citas->medico->email) {
                $citas->medico->notify(new AppointmentScheduled($citas));
            }
        }
    }

    /**
     * Handle the Citas "updated" event.
     */
    public function updated(Citas $citas): void
    {
        if ($citas->isDirty('estado')) {
            $oldStatus = $citas->getOriginal('estado');
            $newStatus = $citas->estado;

            if ($oldStatus !== $newStatus) {
                // Notificar tanto al paciente como al médico
                switch ($newStatus) {
                    case 'pendiente':
                        $citas->paciente->notify(new AppointmentScheduled($citas));
                        if ($citas->medico && $citas->medico->email) {
                            $citas->medico->notify(new AppointmentScheduled($citas));
                        }
                        break;
                    case 'confirmada':
                        $citas->paciente->notify(new AppointmentConfirmed($citas));
                        if ($citas->medico && $citas->medico->email) {
                            $citas->medico->notify(new AppointmentConfirmed($citas));
                        }
                        break;
                    case 'cancelada':
                        $citas->paciente->notify(new AppointmentCancelled($citas));
                        if ($citas->medico && $citas->medico->email) {
                            $citas->medico->notify(new AppointmentCancelled($citas));
                        }
                        break;
                    case 'completada':
                        $citas->paciente->notify(new AppointmentCompleted($citas));
                        if ($citas->medico && $citas->medico->email) {
                            $citas->medico->notify(new AppointmentCompleted($citas));
                        }
                        break;
                }
            }
        }
    }

    /**
     * Handle the Citas "deleted" event.
     */
    public function deleted(Citas $citas): void
    {
        if ($citas->estado !== 'cancelada' && $citas->estado !== 'completada') {
            // Enviar notificación tanto al paciente como al médico cuando se elimina una cita
            $citas->paciente->notify(new AppointmentCancelled($citas));
            
            if ($citas->medico && $citas->medico->email) {
                $citas->medico->notify(new AppointmentCancelled($citas));
            }
        }
    }

    /**
     * Handle the Citas "restored" event.
     */
    public function restored(Citas $citas): void
    {
        //
    }

    /**
     * Handle the Citas "force deleted" event.
     */
    public function forceDeleted(Citas $citas): void
    {
        //
    }
}
