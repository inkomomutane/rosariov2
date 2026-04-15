<?php

namespace Database\Seeders;

use App\Models\Patent;
use Illuminate\Database\Seeder;

class PatentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patents = [
            ['title' => 'Guarda', 'description' => ''],
            ['title' => '1 Cabo', 'description' => ''],
            ['title' => '2 Cabo', 'description' => ''],
            ['title' => 'Sargento', 'description' => ''],
            ['title' => 'Sargento Principal', 'description' => ''],
            ['title' => 'Subinspector', 'description' => ''],
            ['title' => 'Inspetor', 'description' => ''],
            ['title' => 'Inspetor PP', 'description' => ''],
            ['title' => 'Adjunto de Surp', 'description' => ''],
            ['title' => 'Surp', 'description' => ''],
            ['title' => 'Surp P', 'description' => ''],
            ['title' => 'Adjunto de Comissário', 'description' => ''],
            ['title' => '1 Comissário', 'description' => ''],
            ['title' => 'Inspetor Geral', 'description' => '']
        ];

        foreach ($patents as $patent) {
            Patent::create($patent);
        }
    }
}
