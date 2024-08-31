<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $user = User::where('email', 'user@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();
        $statuses = ['pending', 'in_progress', 'completed'];

        foreach (range(1, 10) as $index) {
            Task::create([
                'title' => $faker->sentence,
                'description' => $faker->paragraph,
                'due_date' => $faker->dateTimeBetween('now', '+1 month'),
                'status' => $faker->randomElement($statuses),
                'created_by' => $faker->randomElement([$user->id, $admin->id]),
            ]);
        }
    }
}
