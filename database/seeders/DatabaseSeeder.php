<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {   
         User::factory(10)->create();

         User::factory()->create([
             'name' => 'Alaa Breim',
             'email' => 'breim.alaa@gmail.com',
         ]);

         Institution::factory(10)->create();
    }
}
