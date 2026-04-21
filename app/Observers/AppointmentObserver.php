<?php

namespace App\Observers;

use App\Models\Appointment;
use InvalidArgumentException;

class AppointmentObserver
{
    /**
     * Idopont adatok ellenorzese mentes elott.
     */
    public function saving(Appointment $appointment): void
    {
        if ($appointment->doctor_id === $appointment->patient_id) {
            throw new InvalidArgumentException('Az orvos es a beteg nem lehet ugyanaz a felhasznalo.');
        }

        if (empty($appointment->status)) {
            $appointment->status = 'scheduled';
        }
    }
}
