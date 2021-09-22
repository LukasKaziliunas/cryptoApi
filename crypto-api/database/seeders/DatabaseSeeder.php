<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);
        \App\Models\User::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        
        \App\Models\Asset::factory(3)->create();
    }
}
