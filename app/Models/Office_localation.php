<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office_localation extends Model
{
    /** @use HasFactory<\Database\Factories\OfficeLocalationFactory> */
    use HasFactory;
    protected $fillable = [
        'room_number'
    ];
    
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
