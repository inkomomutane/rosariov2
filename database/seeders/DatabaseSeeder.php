<?php

namespace Database\Seeders;

use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\Patent;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'email' => 'admin@prm.gov'
        ], [
            'name' => 'Administrador do Sistema',
            'nip' => '123456789',
            'email_verified_at' => now(),
            'first_name' => 'João',
            'second_name' => 'Carlos',
            'last_name' => 'Sitoe',
            'sex' => Sex::MALE,
            'language' => 'Português',
            'birth_date' => '1985-03-15',
            'place_of_birth' => 'Maputo',
            'nationality' => 'Moçambicana',
            'marital_status' => MaritalStatus::MARRIED,
            'id_number' => '110100123456M',
            'id_emitted_at' => '2020-01-15',
            'id_expires_at' => '2030-01-15',
            'tax_number' => '123456789',
            'ingress_date' => '2020-01-01',
            'contacts' => null,
            'primary_contact' => null,
            'emails' => null,
            'primary_email' => null,
            'is_admin' => true,
            'password' => bcrypt('password'),
            'patent_id' => Patent::first()->id
        ]);
    }
}
