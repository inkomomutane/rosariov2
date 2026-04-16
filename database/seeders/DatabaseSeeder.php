<?php

namespace Database\Seeders;

use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate([
            'email' => 'administrator@iris.org'
        ], [
            'name' => 'Administrador',
            'email' =>  'administrator@iris.org',
            'email_verified_at' => now(),
            'password' => \Hash::make('password'),
        ]);
    }
}
