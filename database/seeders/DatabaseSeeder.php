<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Register default user
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
// Rgister Initial Data
use App\Models\UnitsNumber;
use App\Models\Type;
use App\Models\Presentation;
use App\Models\Person;
use App\Models\Client;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->registerDefaultUser();

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

        // Register final consumer client
        $person = Person::create(['name' => Client::$finalConsumerName]);
        Client::create([
            'person_id' => $person->id
        ]);
    }

    /**
     * Register default user
     */
    public function registerDefaultUser(): void
    {
        $data = [
            'name' => 'Administrador',
            'email' => 'sd.kettei@gmail.com',
            'password' => '12345678',
        ];

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        event(new Registered($user));

        Auth::login($user);
    }
}
