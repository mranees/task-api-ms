<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin User',
            'email' => 'admin@ex.co',
            'password' => Hash::make('12341234'),
            'is_admin' => true,
        ]);
        User::factory(3)->create();
        Task::factory(10)->create();

    }
}
