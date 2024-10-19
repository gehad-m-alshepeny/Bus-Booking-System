<?php
 
 namespace App\Models\Booking;

 use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Relations\HasMany;
 use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Database\Eloquent\Factories\HasFactory;

 class Trip extends Model
 {
    use HasFactory;

     protected $fillable = ['name', 'start_station_id', 'end_station_id'];
 
   
     public function tripStops(): HasMany
     {
         return $this->hasMany(TripStop::class);
     }
 
    
     public function buses(): HasMany
     {
         return $this->hasMany(Bus::class);
     }
     
     public function startStation(): BelongsTo
     {
         return $this->belongsTo(Station::class, 'start_station_id');
     }
 
  
     public function endStation(): BelongsTo
     {
         return $this->belongsTo(Station::class, 'end_station_id');
     }
 }
 