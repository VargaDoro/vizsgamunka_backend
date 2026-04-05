<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Appointment;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'social_security_number',
        'birth_date',
        'country',
        'city',
        'postal_code',
        'street_address',
        'phone_number',
        'license_number',
        'specialization',
        'office_location_id',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDoctor()
    {
        return $this->role === 'doctor';
    }
        public function isPatient()
    {
        return $this->role === 'patient';
    }

    public function isDoctorOrAdmin()
    {
        return $this->role === 'doctor' || $this->role === 'admin' ;
    }

    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id', 'id');
    }

    public function patientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    public function officeLocation()
    {
        return $this->belongsTo(OfficeLocation::class, 'office_location_id');
    }

    // Orvos páciensei
    public function patients()
    {
        return $this->belongsToMany(
            User::class,
            'appointments',
            'doctor_id',
            'patient_id'
        )->where('users.role', 'patient')->distinct();
    }

    public function doctors()
    {
        return $this->belongsToMany(
            User::class,
            'appointments',
            'patient_id',
            'doctor_id'
        )->where('users.role', 'doctor')->distinct();
    }
}
