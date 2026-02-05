<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document_type extends Model
{
    /** @use HasFactory<\Database\Factories\DocumentTypeFactory> */
    use HasFactory;
    protected $fillable = [
        'type',
    ];
    public function documents()
    {
        return $this->hasMany(Document::class, 'type', 'type');
    }

}
