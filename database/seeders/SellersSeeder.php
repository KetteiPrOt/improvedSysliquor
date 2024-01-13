<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Person;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class SellersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $depositName = Warehouse::$depositName;
        $liquorStoreName = Warehouse::$liquorStoreName;
        $sellersData = [
            ['Super Admin', 'Fernando Joel Mero Trávez', 'sd.kettei@gmail.com', 'SktUp3*a1', $depositName],
            ['Super Admin', 'Patricia Elizabeth Trávez Mero', 'patricia@gmail.com', 'SktUp3*a1', $depositName],
            ['Super Admin', 'Orley Fabian Mero Bailón', 'orley@gmail.com', 'SktUp3*a1', $depositName],
            ['seller', 'Sra. Maira', 'sra.maira@gmail.com', 'SktUp3*a1', $depositName],
            ['seller', 'Sr. Lenonardo', 'sr.leonardo@gmail.com', 'SktUp3*a1', $liquorStoreName],
            ['seller', 'Sra. Edita', 'sr.edita@gmail.com', 'SktUp3*a1', $liquorStoreName],
        ];
        foreach($sellersData as $sellerData){
            $this->registerSeller($sellerData);
        }
    }

    /**
     * Register an seller.
     */
    private function registerSeller(array $sellerData): void
    {
        $name = $sellerData[1];
        $email = $sellerData[2];
        $password = $sellerData[3];
        $warehouse = Warehouse::where('name', $sellerData[4])->first();
        $user = $this->registerUser($name, $email, $password);
        $role = $sellerData[0];
        $user->assignRole($role);
        $person = Person::create([
            'name' => $name,
            'email' => $email
        ]);
        Seller::create([
            'user_id' => $user->id,
            'person_id' => $person->id,
            'warehouse_id' => $warehouse->id
        ]);
    }

    /**
     * Register an user.
     */
    private function registerUser($name, $email, $password): User
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        event(new Registered($user));
        return $user;
    }
}
