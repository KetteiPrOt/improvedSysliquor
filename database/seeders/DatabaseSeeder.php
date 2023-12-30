<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// Register default user
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->registerDefaultUser();
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
