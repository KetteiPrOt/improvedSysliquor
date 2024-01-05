<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitsNumber;
use App\Models\Type;
use App\Models\Presentation;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Register units numbers
        $units_numbers = [1, 6, 12];
        foreach($units_numbers as $units_number){
            UnitsNumber::create(['units' => $units_number]);
        }

        // Register types of products
        $types = ['whisky', 'vino', 'ron', 'aguardiente', 'tequila'];
        foreach($types as $type){
            Type::create(['name' => $type]);
        }

        // Register presentation of products
        $presentations = [200, 375, 750, 1000, 1500];
        foreach($presentations as $presentation){
            Presentation::create(['content' => $presentation]);
        }
    }
}
