<?php

namespace Tests\Feature\Patient;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientFunctionsTest extends TestCase
{
    use RefreshDatabase;

    protected $patient;
    protected $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        // Create patient
        $this->patient = User::factory()->create([
            'role' => 'patient'
        ]);

        // Create doctor
        $this->doctor = User::factory()->create([
            'role' => 'doctor'
        ]);
    }

    /** @test */
    public function patient_can_list_doctors()
    {
        $response = $this->actingAs($this->patient)->get('/api/doctors');
        $response->assertStatus(200);
    }

    /** @test */
    public function patient_can_view_single_doctor()
    {
        $response = $this->actingAs($this->patient)
            ->get('/api/doctors/' . $this->doctor->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function patient_can_view_doctor_appointments()
    {
        Appointment::factory()->create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_time' => now()->addDay(),
            'status' => 'foglalva'
        ]);

        $response = $this->actingAs($this->patient)
            ->get("/api/doctors/{$this->doctor->id}/appointments");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id','doctor_id','patient_id','appointment_time','status']
                 ]);
    }

    /** @test */
    public function patient_can_book_appointment()
    {
        $payload = [
            'appointment_time' => now()->addDay()->format('Y-m-d H:i:s')
        ];

        $response = $this->actingAs($this->patient)
            ->post("/api/doctors/{$this->doctor->id}/appointments", $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id
        ]);
    }

    /** @test */
    public function patient_can_list_own_appointments()
    {
        Appointment::factory()->create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_time' => now()->addDay(),
            'status' => 'foglalva'
        ]);

        $response = $this->actingAs($this->patient)
            ->get('/api/appointments');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => [
                         'id',
                         'doctor_name',
                         'specialization',
                         'office_location',
                         'appointment_time',
                         'status'
                     ]
                 ]);
    }

    /** @test */
    public function patient_can_delete_own_appointment()
    {
        $appointment = Appointment::factory()->create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_time' => now()->addDay(),
            'status' => 'foglalva'
        ]);

        $response = $this->actingAs($this->patient)
            ->delete("/api/appointments/{$appointment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('appointments', [
            'id' => $appointment->id
        ]);
    }
}