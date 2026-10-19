<?php

namespace Database\Seeders;

use App\Enum\CaseType;
use App\Enum\MaritalStatus;
use App\Enum\Sex;
use App\Models\CasePricing;
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

        User::updateOrCreate([
            'email' => 'administrator@iris.org'
        ], [
            'name' => 'Administrador',
            'email' =>  'administrator@iris.org',
            'email_verified_at' => now(),
            'password' => \Hash::make('password'),
        ]);

        User::updateOrCreate([
            'email' => 'mutamira@telemed.com'
        ], [
            'name' => 'Mutamira',
            'email' =>  'mutamira@telemed.com',
            'email_verified_at' => now(),
            'password' => \Hash::make('MeuPassword@123'),
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
