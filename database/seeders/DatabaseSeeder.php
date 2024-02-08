<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitsNumber;
use App\Models\Type;
use App\Models\Presentation;
use App\Models\Person;
use App\Models\Client;
use App\Models\Provider;
use App\Models\Warehouse;
use App\Models\MovementCategory;
use App\Models\MovementType;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Register units numbers
        $units_numbers = [1, 6, 12];
        foreach($units_numbers as $units_number){
            UnitsNumber::create(['units' => $units_number]);
        }

        // Register types of products
        $types = [
            'WHISKY',
            'VINO',
            'RON',
            'AGUARDIENTE',
            'TEQUILA',
            'COCKTAIL',
            'ESPUMANTE',
            'SANGRIA',
            'VODKA'
        ];
        foreach($types as $type){
            Type::create(['name' => $type]);
        }

        // Register presentation of products
        $presentations = [200, 375, 750, 1000, 1500, 600, 700];
        foreach($presentations as $presentation){
            Presentation::create(['content' => $presentation]);
        }

        // Register final consumer client
        $person = Person::create(['name' => Client::$finalConsumerName]);
        Client::create([
            'person_id' => $person->id
        ]);

        // Register warehouses
        $warehouses = [Warehouse::$depositName, Warehouse::$liquorStoreName];
        foreach($warehouses as $warehouse){
            Warehouse::create(['name' => $warehouse]);
        }

        // Register Roles and Permissions
        $this->call([RolesSeeder::class]);

        // Register Sellers
        $this->call([SellersSeeder::class]);

        // Register Movement Categories
        $incomeCategory = MovementCategory::create([
            'name' => MovementCategory::$incomeName
        ]);
        $egressCategory = MovementCategory::create([
            'name' => MovementCategory::$expenseName
        ]);

        // Register Movement Types
        $incomeTypes = [MovementType::$initialInventoryName, MovementType::$purchaseName];
        foreach($incomeTypes as $type){
            MovementType::create([
                'name' => $type,
                'movement_category_id' => $incomeCategory->id
            ]);
        }
        $egressTypes = [MovementType::$saleName, 'Donación', 'Publicidad'];
        foreach($egressTypes as $type){
            MovementType::create([
                'name' => $type,
                'movement_category_id' => $egressCategory->id
            ]);
        }

        // Register Products
        $this->call([ProductsSeeder::class]);
    }
}
