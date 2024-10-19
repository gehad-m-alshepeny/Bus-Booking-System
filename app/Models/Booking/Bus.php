<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'seat_count'];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
