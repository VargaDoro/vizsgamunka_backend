<?php

namespace Tests\Feature\Doctor;

use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DoctorFunctionsTest extends TestCase
{
	use RefreshDatabase;

	protected User $doctor;
	protected User $patient;

	protected function setUp(): void
	{
		parent::setUp();

		$this->doctor = User::factory()->create([
			'role' => 'doctor',
			'email' => 'doctor_feature@test.com',
			'specialization' => 'Kardiologus',
		]);

		$this->patient = User::factory()->create([
			'role' => 'patient',
			'email' => 'patient_feature@test.com',
			'social_security_number' => '123456789',
		]);

		Appointment::create([
			'doctor_id' => $this->doctor->id,
			'patient_id' => $this->patient->id,
			'appointment_time' => now()->addDay(),
			'status' => 'scheduled',
		]);
	}

	/** @test */
	public function doctor_can_list_own_patients(): void
	{
		Sanctum::actingAs($this->doctor);

		$response = $this->getJson('/api/doctor/patients');

		$response->assertStatus(200)
			->assertJsonFragment([
				'id' => $this->patient->id,
				'email' => $this->patient->email,
			]);
	}

	/** @test */
	public function doctor_can_create_patient(): void
	{
		Sanctum::actingAs($this->doctor);

		$response = $this->postJson('/api/doctor/patients', [
			'name' => 'Teszt Beteg',
			'email' => 'ujbeteg@test.com',
			'social_security_number' => '987654321',
			'password' => 'password123',
		]);

		$response->assertStatus(201);

		$this->assertDatabaseHas('users', [
			'email' => 'ujbeteg@test.com',
			'role' => 'patient',
		]);
	}

	/** @test */
	public function doctor_can_view_single_related_patient(): void
	{
		Sanctum::actingAs($this->doctor);

		$response = $this->getJson('/api/doctor/patients/' . $this->patient->id);

		$response->assertStatus(200)
			->assertJsonStructure([
				'patient' => ['id', 'name', 'email'],
				'appointments',
			]);
	}

	/** @test */
	public function doctor_cannot_view_unrelated_patient(): void
	{
		Sanctum::actingAs($this->doctor);

		$otherPatient = User::factory()->create([
			'role' => 'patient',
		]);

		$response = $this->getJson('/api/doctor/patients/' . $otherPatient->id);

		$response->assertStatus(404);
	}

	/** @test */
	public function doctor_can_list_document_types(): void
	{
		Sanctum::actingAs($this->doctor);

		DB::table('document_types')->insert([
			['type' => 'Lelet'],
			['type' => 'Zarojelentes'],
		]);

		$response = $this->getJson('/api/doctor/document-types');

		$response->assertStatus(200)
			->assertJsonFragment(['type' => 'Lelet'])
			->assertJsonFragment(['type' => 'Zarojelentes']);
	}

	/** @test */
	public function doctor_can_upload_document(): void
	{
		Sanctum::actingAs($this->doctor);
		Storage::fake('public');

		$documentTypeId = DB::table('document_types')->insertGetId([
			'type' => 'Teszt Dokumentum',
		]);

		$response = $this->postJson('/api/doctor/documents', [
			'file' => UploadedFile::fake()->create('lelet.pdf', 100, 'application/pdf'),
			'taj' => $this->patient->social_security_number,
			'document_type_id' => $documentTypeId,
		]);

		$response->assertStatus(201)
			->assertJsonStructure([
				'id',
				'doctor_id',
				'patient_id',
				'document_type_id',
				'file_path',
				'created_at',
			]);

		$this->assertDatabaseHas('documents', [
			'doctor_id' => $this->doctor->id,
			'patient_id' => $this->patient->id,
			'document_type_id' => $documentTypeId,
		]);
	}

	/** @test */
	public function doctor_can_list_own_appointments(): void
	{
		Sanctum::actingAs($this->doctor);

		$response = $this->getJson('/api/doctor/appointments');

		$response->assertStatus(200)
			->assertJsonStructure([
				'*' => ['id', 'patient_name', 'appointment_time', 'status'],
			]);
	}

	/** @test */
	public function doctor_can_crud_prescription(): void
	{
		Sanctum::actingAs($this->doctor);

		$storeResponse = $this->postJson('/api/doctor/prescriptions', [
			'patient_id' => $this->patient->id,
			'medicine_name' => 'Aspirin',
			'dosage' => '2x1',
			'issued_at' => now()->toDateString(),
			'valid_until' => now()->addDays(10)->toDateString(),
		]);

		$storeResponse->assertStatus(201);
		$prescriptionId = $storeResponse->json('id');

		$this->getJson('/api/doctor/prescriptions')
			->assertStatus(200)
			->assertJsonFragment(['medicine_name' => 'Aspirin']);

		$this->getJson('/api/doctor/prescriptions/' . $prescriptionId)
			->assertStatus(200)
			->assertJsonFragment(['id' => $prescriptionId]);

		$this->patchJson('/api/doctor/prescriptions/' . $prescriptionId, [
			'dosage' => '1x1',
		])->assertStatus(200)
			->assertJsonFragment(['dosage' => '1x1']);

		$this->deleteJson('/api/doctor/prescriptions/' . $prescriptionId)
			->assertStatus(200);

		$this->assertDatabaseMissing('prescriptions', [
			'id' => $prescriptionId,
		]);
	}

	/** @test */
	public function guest_cannot_access_doctor_endpoints(): void
	{
		$this->getJson('/api/doctor/patients')->assertStatus(401);
		$this->getJson('/api/doctor/appointments')->assertStatus(401);
		$this->postJson('/api/doctor/documents', [])->assertStatus(401);
	}
}

/*
Futtatasi parancs:
php artisan test --filter=DoctorFunctionsTest
*/
