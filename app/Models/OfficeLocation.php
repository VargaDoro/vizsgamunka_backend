<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'building',
    ];

    // A doctors kapcsolat a User modelre mutat,
    // mert nálatok NINCS külön Doctor model
    public function doctors()
    {
        return $this->hasMany(User::class, 'office_location_id');
    }
}