<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'social_security_number',
        'birth_date',
        'country',
        'city',
        'postal_code',
        'street_address',
        'phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
