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
        $u1 = \App\Models\User::factory()->create(['name' => 'Bob', 'email' => 'bob@example.com']);
        $u2 = \App\Models\User::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);
        // \App\Models\User::factory(10)->create();
    }
}
