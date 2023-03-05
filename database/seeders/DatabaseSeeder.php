<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Abraham Gonzalez',
            'email' => 'abraham@test.com',
            'password' => '$2y$10$UB4fU4hlW7SFD0dn3IaH9OhFWU5L0MSHBxcNW6PC/l6kODSyDCC2S', // DictadorMarico69?
        ]);

        \App\Models\User::factory(50)->create();


    }
}
