<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityLogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@example.com')->first();
        $admin = User::where('email', 'admin@example.com')->first();
        $tasks = Task::all();

        foreach ($tasks as $task) {
            ActivityLog::create([
                'task_id' => $task->id,
                'user_id' => $task->created_by,
                'action' => 'created',
                'details' => 'Task "' . $task->title . '" was created.',
            ]);

            ActivityLog::create([
                'task_id' => $task->id,
                'user_id' => $task->created_by == $user->id ? $admin->id : $user->id,
                'action' => 'updated',
                'details' => 'Task "' . $task->title . '" status updated by ' . ($task->created_by == $user->id ? 'Admin' : 'User') . '.',
            ]);

            // Add additional logs
            ActivityLog::create([
                'task_id' => $task->id,
                'user_id' => $task->created_by == $user->id ? $admin->id : $user->id,
                'action' => 'completed',
                'details' => 'Task "' . $task->title . '" was marked as completed.',
            ]);
        }
    }
}
