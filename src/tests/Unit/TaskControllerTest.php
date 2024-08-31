<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_store_a_task()
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'Test Task',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'pending',
            'description' => 'This is a test task',
            'assigned_to' => null,
        ];

        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('task.storeTask'), $data); 


        $response->assertStatus(201); 
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    /** @test */
    public function it_fails_to_store_a_task_without_required_fields()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Prepare invalid request data
        $data = [
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'status' => 'pending',
            'description' => 'This is a test task',
        ];

        // Simulate the request with authentication
        $response = $this->actingAs($user, 'sanctum')
            ->postJson(route('task.storeTask'), $data); // Use named route

        // Assert validation failure
        $response->assertStatus(422); // Validation error
        $response->assertJsonValidationErrors(['title']);
    }
}