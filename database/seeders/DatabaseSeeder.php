<?php

namespace Database\Seeders;

use App\Enum\CaseType;
use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\CasePricing;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;
use Random\RandomException;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @throws RandomException
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user1 = User::updateOrCreate([
            'email' => 'administrator@iris.org'
        ], [
            'name' => 'Administrador',
            'email' =>  'administrator@iris.org',
            'email_verified_at' => now(),
            'password' => \Hash::make('password'),
        ]);

        Person::updateOrCreate([
            'user_id' => $user1->id
        ],[
            'name' => 'Administrador',
            'last_name' => 'Iris',
            'gender' => Sex::MALE,
            'marital_status' => MaritalStatus::SINGLE,
            'birth_date' => now()->subYears(30),
            'is_doctor' => true,
            'is_patient' => false,
        ]);



       $user2 =  User::updateOrCreate([
            'email' => 'mutamira@telemed.com'
        ], [
            'name' => 'Mutamira',
            'email' =>  'mutamira@telemed.com',
            'email_verified_at' => now(),
            'password' => \Hash::make('MeuPassword@123'),
        ]);


        Person::updateOrCreate([
            'user_id' => $user2->id
        ],[
            'name' => 'Mutamira',
            'last_name' => 'Telemed',
            'gender' => Sex::MALE,
            'marital_status' => MaritalStatus::MARRIED,
            'birth_date' => now()->subYears(28),
            'is_doctor' => true,
            'is_patient' => false,
        ]);

        $patientUser = User::updateOrCreate([
            'email' => 'paciente@telemed.com'
        ], [
            'name' => 'Paciente',
            'email' =>  'paciente@telemed.com',
            'email_verified_at' => now(),
            'password' => \Hash::make('MeuPassword@123'),
        ]);

        Person::updateOrCreate([
            'user_id' => $patientUser->id
        ],[
            'name' => 'Paciente',
            'last_name' => 'Telemed',
            'gender' => Sex::FEMALE,
            'marital_status' => MaritalStatus::SINGLE,
            'birth_date' => now()->subYears(25),
            'is_doctor' => false,
            'is_patient' => true,
        ]);





        $this->seedCasePricing();


    }


    /**
     * @throws RandomException
     */
    private function seedCasePricing(): void
    {
        $caseTypes = CaseType::cases();
        // use random price between 3000 and 9000 for review phase 1 and 2000 for review phase 2
        foreach ($caseTypes as $caseType) {

            $value = random_int(3000, 9000);
            CasePricing::updateOrCreate([
                'case_type' => $caseType,
                'review_phase' => 1,
            ], [
                'price' => $value
            ]);

            CasePricing::updateOrCreate([
                'case_type' => $caseType,
                'review_phase' => 2,
            ], [
                'price' =>  random_int($value - 1000, $value - 500)
            ]);
        }
    }
}
