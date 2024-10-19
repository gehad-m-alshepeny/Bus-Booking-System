<?php
 
 namespace App\Models\Booking;

 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 
 class TripStop extends Model
 {
     use HasFactory;

     protected $fillable = ['trip_id', 'station_id', 'stop_order'];
 
     public function trip(): BelongsTo
     {
         return $this->belongsTo(Trip::class);
     }
 
  
     public function station(): BelongsTo
     {
         return $this->belongsTo(Station::class);
     }
 }
 