<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Document extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'document_type_id',
        'file_path',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function documentType()
    {
        return $this->belongsTo(Document_type::class, 'document_type_id');
    }

    public function type()
    {
        return $this->documentType();
    }
}
