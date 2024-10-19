<?php

namespace App\Models\Booking;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Station extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    public function tripStops(): HasMany
    {
        return $this->hasMany(TripStop::class);
    }
}
