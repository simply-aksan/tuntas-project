<?php

namespace Tests\Feature;

use App\Livewire\TaskManager;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get('/tasks');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_tasks_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tasks');

        $response->assertStatus(200);
        $response->assertSeeLivewire(TaskManager::class);
    }

    public function test_tasks_list_only_displays_authenticated_user_tasks(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskA = Task::factory()->create([
            'user_id' => $userA->id,
            'title' => 'User A Secret Assignment',
        ]);

        $taskB = Task::factory()->create([
            'user_id' => $userB->id,
            'title' => 'User B Private Project',
        ]);

        Livewire::actingAs($userA)
            ->test(TaskManager::class)
            ->assertSee('User A Secret Assignment')
            ->assertDontSee('User B Private Project');
    }

    public function test_tasks_can_be_filtered_by_status(): void
    {
        $user = User::factory()->create();

        $pendingTask = Task::factory()->pending()->create([
            'user_id' => $user->id,
            'title' => 'Pending Homework',
        ]);

        $completedTask = Task::factory()->completed()->create([
            'user_id' => $user->id,
            'title' => 'Completed Exam',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('statusFilter', 'pending')
            ->assertSee('Pending Homework')
            ->assertDontSee('Completed Exam')
            ->set('statusFilter', 'completed')
            ->assertSee('Completed Exam')
            ->assertDontSee('Pending Homework');
    }

    public function test_tasks_can_be_filtered_by_priority(): void
    {
        $user = User::factory()->create();

        $highTask = Task::factory()->create([
            'user_id' => $user->id,
            'priority' => 'high',
            'title' => 'High Priority Submission',
        ]);

        $lowTask = Task::factory()->create([
            'user_id' => $user->id,
            'priority' => 'low',
            'title' => 'Low Priority Reading',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('priorityFilter', 'high')
            ->assertSee('High Priority Submission')
            ->assertDontSee('Low Priority Reading')
            ->set('priorityFilter', 'low')
            ->assertSee('Low Priority Reading')
            ->assertDontSee('High Priority Submission');
    }

    public function test_tasks_can_be_filtered_by_category(): void
    {
        $user = User::factory()->create();

        $mathCategory = Category::factory()->create(['user_id' => $user->id, 'name' => 'Mathematics']);
        $physicsCategory = Category::factory()->create(['user_id' => $user->id, 'name' => 'Physics']);

        $mathTask = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $mathCategory->id,
            'title' => 'Algebra Quiz',
        ]);

        $physicsTask = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $physicsCategory->id,
            'title' => 'Kinematics Lab',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('categoryFilter', (string) $mathCategory->id)
            ->assertSee('Algebra Quiz')
            ->assertDontSee('Kinematics Lab')
            ->set('categoryFilter', (string) $physicsCategory->id)
            ->assertSee('Kinematics Lab')
            ->assertDontSee('Algebra Quiz');
    }

    public function test_tasks_can_be_searched_by_title_or_description(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Chemistry Lab Report',
            'description' => 'Titration experiment findings',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'History Essay',
            'description' => 'World War II timeline analysis',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('search', 'Titration')
            ->assertSee('Chemistry Lab Report')
            ->assertDontSee('History Essay')
            ->set('search', 'History')
            ->assertSee('History Essay')
            ->assertDontSee('Chemistry Lab Report');
    }

    public function test_user_can_create_task_with_valid_data(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->set('title', 'Finish Midterm Project')
            ->set('description', 'Comprehensive documentation and code')
            ->set('category_id', $category->id)
            ->set('priority', 'high')
            ->set('status', 'in_progress')
            ->set('due_date', '2026-12-01')
            ->call('createTask')
            ->assertSet('showCreateModal', false)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Finish Midterm Project',
            'priority' => 'high',
            'status' => 'in_progress',
            'due_date' => '2026-12-01 00:00:00',
        ]);
    }

    public function test_create_task_validation_requires_title_and_valid_enums(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('title', '')
            ->set('priority', 'invalid-priority')
            ->set('status', 'invalid-status')
            ->call('createTask')
            ->assertHasErrors(['title', 'priority', 'status']);
    }

    public function test_user_cannot_assign_category_owned_by_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $categoryB = Category::factory()->create(['user_id' => $userB->id]);

        Livewire::actingAs($userA)
            ->test(TaskManager::class)
            ->set('title', 'Unauthorized Category Attempt')
            ->set('category_id', $categoryB->id)
            ->call('createTask')
            ->assertHasErrors(['category_id']);

        $this->assertDatabaseMissing('tasks', [
            'title' => 'Unauthorized Category Attempt',
        ]);
    }

    public function test_user_can_view_task_details(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Detail View Test',
            'description' => 'Specific instructions inside task detail',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->call('openDetailModal', $task->id)
            ->assertSet('showDetailModal', true)
            ->assertSet('selectedTask.id', $task->id)
            ->assertSee('Detail View Test')
            ->assertSee('Specific instructions inside task detail');
    }

    public function test_user_cannot_view_task_details_of_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskB = Task::factory()->create(['user_id' => $userB->id]);

        Livewire::actingAs($userA)
            ->test(TaskManager::class)
            ->call('openDetailModal', $taskB->id)
            ->assertForbidden();
    }

    public function test_user_can_edit_and_update_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Initial Title',
            'priority' => 'low',
            'status' => 'pending',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->call('openEditModal', $task->id)
            ->assertSet('showEditModal', true)
            ->assertSet('title', 'Initial Title')
            ->set('title', 'Updated Title')
            ->set('priority', 'high')
            ->set('status', 'in_progress')
            ->call('updateTask')
            ->assertSet('showEditModal', false)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);
    }

    public function test_user_cannot_update_task_of_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskB = Task::factory()->create(['user_id' => $userB->id]);

        Livewire::actingAs($userA)
            ->test(TaskManager::class)
            ->call('openEditModal', $taskB->id)
            ->assertForbidden();
    }

    public function test_user_can_delete_task(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Task To Be Removed',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->call('confirmDelete', $task->id)
            ->assertSet('showDeleteModal', true)
            ->assertSet('taskIdBeingDeleted', $task->id)
            ->call('deleteTask')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_user_cannot_delete_task_of_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $taskB = Task::factory()->create(['user_id' => $userB->id]);

        Livewire::actingAs($userA)
            ->test(TaskManager::class)
            ->call('confirmDelete', $taskB->id)
            ->assertForbidden();

        $this->assertDatabaseHas('tasks', ['id' => $taskB->id]);
    }

    public function test_user_can_manage_and_toggle_status_with_completed_at_lifecycle(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->pending()->create(['user_id' => $user->id]);

        $this->assertNull($task->completed_at);

        // Transition to completed
        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->call('updateStatus', $task->id, 'completed');

        $this->assertEquals('completed', $task->fresh()->status);
        $this->assertNotNull($task->fresh()->completed_at);

        // Transition back to in_progress clears completed_at
        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->call('updateStatus', $task->id, 'in_progress');

        $this->assertEquals('in_progress', $task->fresh()->status);
        $this->assertNull($task->fresh()->completed_at);
    }

    public function test_user_can_manage_priority(): void
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'priority' => 'low']);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->call('updatePriority', $task->id, 'high');

        $this->assertEquals('high', $task->fresh()->priority);
    }

    public function test_deadline_management_and_overdue_filtering(): void
    {
        $user = User::factory()->create();

        $overdueTask = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Late Lab Report',
            'status' => 'pending',
            'due_date' => now()->subDays(3)->format('Y-m-d'),
        ]);

        $futureTask = Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Upcoming Semester Final',
            'status' => 'pending',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('statusFilter', 'overdue')
            ->assertSee('Late Lab Report')
            ->assertDontSee('Upcoming Semester Final');
    }
}
