<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    //public $timestamps = false; ?????????????????? kell? mert a main branch-ben van ez az 1 sor plusz még eszti rakta bele, vmilyen hibara ai ezt dobta

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'type',
        'file_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function type()
    {
        return $this->belongsTo(DocumentType::class, 'type', 'type');
    }
}
