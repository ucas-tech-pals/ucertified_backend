<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Document;
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


         Institution::factory()->create([
             'name' => 'UCAS',
             'email' => 'admin@ucas.edu.ps',
            ]);

         Institution::factory(3)->create();

         Institution::all()->each(function (Institution $institution) {
                $institution->documents()
                    ->saveMany(Document::factory(10)->make());
            });

            User::all()->each(function (User $user) {
                Document::all()->random(3)->each(
                    function (Document $document) use ($user) {
                        $document->update(['user_id' => $user->id]);
                    }
                );
            });
    }
}
