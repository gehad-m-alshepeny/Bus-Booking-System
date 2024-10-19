<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking\Station;

class StationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stations = [
            ['name' => 'Cairo'],
            ['name' => 'Giza'],
            ['name' => 'AlFayyum'],
            ['name' => 'Beni-Suef'],
            ['name' => 'AlMinya'],
            ['name' => 'Asyut'],
            ['name' => 'Sohag'],
            ['name' => 'Qena'],
            ['name' => 'Luxor'],
            ['name' => 'Aswan'],
        ];

        foreach ($stations as $station) {
            Station::updateOrCreate(
                ['name' => $station['name']], 
                ['name' => $station['name']] 
            );
        }
    }
}
