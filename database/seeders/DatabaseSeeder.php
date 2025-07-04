<?php

namespace Database\Seeders;

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

        User::factory()->create([
            'name' => 'Reinaldo',
            'email' => 'rei@email.com',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);
        User::factory()->create([
            'name' => 'Bob',
            'email' => 'bob@email.com',
            'password' => bcrypt('123456'),
            'role' => 'funcionario',
        ]);
        User::factory()->create([
            'name' => 'Ana',
            'email' => 'ana@email.com',
            'password' => bcrypt('123456'),
            'role' => 'funcionario',
        ]);

        $this->call(\Database\Seeders\FormularioExemploSeeder::class);
        $this->call(\Database\Seeders\FormularioExemploRespostasSeeder::class);
    }
}
