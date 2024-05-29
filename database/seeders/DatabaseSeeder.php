<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FontePagadora;
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

        User::factory()->create([
            'name' => 'STPG-FEB',
            'email' => 'stpg.feb@unesp.br',
        ]);

        FontePagadora::factory()->createMany([
            ['nome' => 'PROAP/AUXPE'],
            ['nome' => 'Tesouro'],
        ]);
    }
}
