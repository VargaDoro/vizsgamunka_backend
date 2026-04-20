<?php

namespace Tests\Feature\Patient;

use App\Models\Appointment;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PatientFunctionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $patient;
    protected User $doctor;

    protected function setUp(): void
    {
        parent::setUp();

        // Create patient
        $this->patient = User::factory()->create([
            'role' => 'patient',
            'email' => 'patient_test@test.com',
        ]);

        // Create doctor
        $this->doctor = User::factory()->create([
            'role' => 'doctor',
            'email' => 'doctor_test@test.com',
            'specialization' => 'Kardiológus',
        ]);
    }

    /** @test */
    public function patient_can_list_doctors(): void
    {
        Sanctum::actingAs($this->patient);

        $response = $this->getJson('/api/doctors');

        $response->assertStatus(200);
    }

    /** @test */
    public function patient_can_view_single_doctor(): void
    {
        Sanctum::actingAs($this->patient);

        $response = $this->getJson('/api/doctors/' . $this->doctor->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function patient_can_view_doctor_appointments(): void
    {
        Sanctum::actingAs($this->patient);

        Appointment::factory()->create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_time' => now()->addDay(),
            'status' => 'foglalva',
        ]);

        $response = $this->getJson("/api/doctors/{$this->doctor->id}/appointments");

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'doctor_id', 'patient_id', 'appointment_time', 'status']
            ]);
    }

    /** @test */
    public function patient_can_book_appointment(): void
    {
        Sanctum::actingAs($this->patient);

        $payload = [
            'appointment_time' => now()->addDay()->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson("/api/doctors/{$this->doctor->id}/appointments", $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('appointments', [
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_time' => $payload['appointment_time'],
        ]);
    }

    /** @test */
    public function patient_cannot_book_already_taken_appointment_slot(): void
    {
        Sanctum::actingAs($this->patient);

        $time = now()->addDays(2)->format('Y-m-d H:i:s');

        // Foglalja le előre valaki más ugyanarra az orvosra ugyanazt az időpontot
        $otherPatient = User::factory()->create(['role' => 'patient']);
        Appointment::factory()->create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $otherPatient->id,
            'appointment_time' => $time,
            'status' => 'foglalva',
        ]);

        $response = $this->postJson("/api/doctors/{$this->doctor->id}/appointments", [
            'appointment_time' => $time
        ]);

        $response->assertStatus(409);
    }

    /** @test */
    public function patient_cannot_book_past_appointment_time(): void
    {
        Sanctum::actingAs($this->patient);

        $response = $this->postJson("/api/doctors/{$this->doctor->id}/appointments", [
            'appointment_time' => now()->subDay()->format('Y-m-d H:i:s')
        ]);

        // Laravel validate → 422
        $response->assertStatus(422);
    }

    /** @test */
    public function patient_can_list_own_appointments(): void
    {
        Sanctum::actingAs($this->patient);

        Appointment::factory()->create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_time' => now()->addDay(),
            'status' => 'foglalva',
        ]);

        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'doctor_name',
                    'specialization',
                    'office_location',
                    'appointment_time',
                    'status',
                ]
            ]);
    }

    /** @test */
    public function patient_can_delete_own_appointment(): void
    {
        Sanctum::actingAs($this->patient);

        $appointment = Appointment::factory()->create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'appointment_time' => now()->addDay(),
            'status' => 'foglalva',
        ]);

        $response = $this->deleteJson("/api/appointments/{$appointment->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('appointments', [
            'id' => $appointment->id,
        ]);
    }

    /** @test */
    public function patient_cannot_delete_other_patients_appointment(): void
    {
        Sanctum::actingAs($this->patient);

        $otherPatient = User::factory()->create(['role' => 'patient']);

        $appointment = Appointment::factory()->create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $otherPatient->id,
            'appointment_time' => now()->addDays(3),
            'status' => 'foglalva',
        ]);

        $response = $this->deleteJson("/api/appointments/{$appointment->id}");

        // A ti implementációtok: where('patient_id', Auth::id())->findOrFail($id)
        // → nem találja → 404
        $response->assertStatus(404);

        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
        ]);
    }

    /** @test */
    public function patient_can_list_specializations(): void
    {
        Sanctum::actingAs($this->patient);

        // plusz orvos másik szakkal
        User::factory()->create([
            'role' => 'doctor',
            'specialization' => 'Bőrgyógyász',
        ]);

        $response = $this->getJson('/api/specializations');

        $response->assertStatus(200)
            ->assertJsonFragment(['Kardiológus'])
            ->assertJsonFragment(['Bőrgyógyász']);
    }

    /** @test */
    public function patient_can_list_own_documents(): void
    {
        Sanctum::actingAs($this->patient);

        $documentTypeId = DB::table('document_types')->insertGetId([
            'type' => 'Teszt dokumentum',
        ]);

        Document::create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $this->patient->id,
            'document_type_id' => $documentTypeId,
            'file_path' => 'documents/test.pdf',
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/documents');

        $response->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['id', 'doctor_name', 'type', 'created_at']
            ]);
    }

    /** @test */
    public function patient_cannot_see_other_patients_documents(): void
    {
        Sanctum::actingAs($this->patient);

        $otherPatient = User::factory()->create(['role' => 'patient']);
        $secretTypeId = DB::table('document_types')->insertGetId([
            'type' => 'Titkos dokumentum',
        ]);

        Document::create([
            'doctor_id' => $this->doctor->id,
            'patient_id' => $otherPatient->id,
            'document_type_id' => $secretTypeId,
            'file_path' => 'documents/secret.pdf',
            'created_at' => now(),
        ]);

        $response = $this->getJson('/api/documents');

        $response->assertStatus(200)
            ->assertJsonMissing(['type' => 'Titkos dokumentum']);
    }

    /** @test */
    public function guest_cannot_access_patient_documents(): void
    {
        $response = $this->getJson('/api/documents');
        $response->assertStatus(401);
    }
}

/*
Futtatási parancs:
php artisan test --filter=PatientFunctionsTest
*/