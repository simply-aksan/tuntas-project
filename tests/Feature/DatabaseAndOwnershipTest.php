<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseAndOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_categories_and_tasks(): void
    {
        $user = User::factory()->create();

        $category = Category::factory()->create(['user_id' => $user->id]);
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $this->assertTrue($user->categories->contains($category));
        $this->assertTrue($user->tasks->contains($task));
        $this->assertEquals($user->id, $category->user->id);
        $this->assertEquals($user->id, $task->user->id);
        $this->assertEquals($category->id, $task->category->id);
    }

    public function test_task_status_lifecycle_sets_and_clears_completed_at(): void
    {
        $user = User::factory()->create();

        // 1. Task created as pending has null completed_at
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'completed_at' => null,
        ]);
        $this->assertNull($task->completed_at);

        // 2. Switching to completed sets completed_at
        $task->update(['status' => 'completed']);
        $this->assertNotNull($task->fresh()->completed_at);

        // 3. Switching back to in_progress clears completed_at
        $task->update(['status' => 'in_progress']);
        $this->assertNull($task->fresh()->completed_at);
    }

    public function test_task_overdue_scope_and_method(): void
    {
        $user = User::factory()->create();

        $overdueTask = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'due_date' => now()->subDays(2),
        ]);

        $futureTask = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'due_date' => now()->addDays(2),
        ]);

        $completedPastTask = Task::factory()->create([
            'user_id' => $user->id,
            'status' => 'completed',
            'due_date' => now()->subDays(2),
        ]);

        $overdueList = Task::overdue()->get();

        $this->assertTrue($overdueList->contains($overdueTask));
        $this->assertFalse($overdueList->contains($futureTask));
        $this->assertFalse($overdueList->contains($completedPastTask));

        $this->assertTrue($overdueTask->isOverdue());
        $this->assertFalse($futureTask->isOverdue());
        $this->assertFalse($completedPastTask->isOverdue());
    }

    public function test_category_and_task_ownership_authorization(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $categoryA = Category::factory()->create(['user_id' => $userA->id]);
        $taskA = Task::factory()->create(['user_id' => $userA->id, 'category_id' => $categoryA->id]);

        // User A owns categoryA and taskA
        $this->assertTrue($userA->can('view', $categoryA));
        $this->assertTrue($userA->can('update', $categoryA));
        $this->assertTrue($userA->can('delete', $categoryA));

        $this->assertTrue($userA->can('view', $taskA));
        $this->assertTrue($userA->can('update', $taskA));
        $this->assertTrue($userA->can('delete', $taskA));

        // User B must NOT have authorization for User A's resources
        $this->assertFalse($userB->can('view', $categoryA));
        $this->assertFalse($userB->can('update', $categoryA));
        $this->assertFalse($userB->can('delete', $categoryA));

        $this->assertFalse($userB->can('view', $taskA));
        $this->assertFalse($userB->can('update', $taskA));
        $this->assertFalse($userB->can('delete', $taskA));
    }
}

