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
            ['Super Admin', 'Administrador', 'sd.kettei@gmail.com', '12345678', $depositName],
            ['seller', 'Patricia Elizabeth Trávez Mero', 'patricia@gmail.com', '12345678', $depositName],
            ['seller', 'Sra. Maira', 'maira@gmail.com', '12345678', $depositName]
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
