<?php

namespace Tests\Feature;

use App\Livewire\Dashboard;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_real_aggregated_statistics(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id, 'name' => 'Algorithms']);

        // 1 pending task
        Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'pending',
            'due_date' => now()->addDays(5),
        ]);

        // 1 in_progress task
        Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'in_progress',
            'due_date' => now()->addDays(2),
        ]);

        // 1 completed task
        Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'completed',
            'completed_at' => now(),
            'due_date' => now()->subDay(),
        ]);

        // 1 overdue task
        Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status' => 'pending',
            'due_date' => now()->subDays(2),
        ]);

        // Task for another user (must NOT count towards statistics)
        $otherUser = User::factory()->create();
        Task::factory()->count(10)->create(['user_id' => $otherUser->id]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->assertSet('totalTasks', null) // tested via view data
            ->assertViewHas('totalTasks', 4)
            ->assertViewHas('pendingTasks', 2) // 1 regular + 1 overdue
            ->assertViewHas('inProgressTasks', 1)
            ->assertViewHas('completedTasks', 1)
            ->assertViewHas('overdueTasks', 1)
            ->assertViewHas('totalCategories', 1)
            ->assertViewHas('completionRate', 25)
            ->assertSee('Algorithms')
            ->assertSee('Welcome back, ' . $user->name);
    }

    public function test_dashboard_toggle_complete_updates_task_status_and_completed_at(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'completed_at' => null,
        ]);

        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->call('toggleComplete', $task->id);

        $task->refresh();
        $this->assertEquals('completed', $task->status);
        $this->assertNotNull($task->completed_at);

        // Toggle back to pending
        Livewire::actingAs($user)
            ->test(Dashboard::class)
            ->call('toggleComplete', $task->id);

        $task->refresh();
        $this->assertEquals('pending', $task->status);
        $this->assertNull($task->completed_at);
    }

    public function test_dashboard_cannot_toggle_another_users_task(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskB = Task::factory()->create([
            'user_id' => $userB->id,
            'status' => 'pending',
            'completed_at' => null,
        ]);

        Livewire::actingAs($userA)
            ->test(Dashboard::class)
            ->call('toggleComplete', $taskB->id);

        $taskB->refresh();
        $this->assertEquals('pending', $taskB->status);
        $this->assertNull($taskB->completed_at);
    }
}

