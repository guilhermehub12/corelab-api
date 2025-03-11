<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admininastro',
            'email' => 'admin@email.com',
        ]);
        
        User::factory()->manager()->create([
            'name' => 'Gerente',
            'email' => 'manager@email.com',
        ]);
        
        User::factory()->member()->create([
            'name' => 'Membro',
            'email' => 'member@email.com',
        ]);
        
        User::factory()->count(5)->create();
    }
}
