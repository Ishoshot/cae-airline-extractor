<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'type', 'from', 'to', 'departure', 'arrival', 'meta', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'meta' => 'array',
        'departure' => 'datetime',
        'arrival' => 'datetime',
    ];

    protected function meta(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => json_encode($value),
            get: fn ($value) => json_decode($value),
        );
    }
}
