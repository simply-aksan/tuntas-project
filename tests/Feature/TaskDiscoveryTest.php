<?php

namespace Tests\Feature;

use App\Livewire\TaskManager;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class TaskDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_can_be_discovered_by_searching_title(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Quantum Mechanics Homework',
            'description' => 'Chapter 4 problems',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'World History Presentation',
            'description' => 'Cold War slides',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('search', 'Quantum')
            ->assertSee('Quantum Mechanics Homework')
            ->assertDontSee('World History Presentation');
    }

    public function test_tasks_can_be_discovered_by_searching_description(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Biology Assignment',
            'description' => 'Photosynthesis diagram and report',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Economics Essay',
            'description' => 'Fiscal policy during recession',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('search', 'Photosynthesis')
            ->assertSee('Biology Assignment')
            ->assertDontSee('Economics Essay');
    }

    public function test_task_search_with_no_matching_query_shows_empty_state(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'title' => 'Calculus Test',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('search', 'NonexistentKeywordXYZ')
            ->assertSee('No tasks found')
            ->assertDontSee('Calculus Test');
    }

    public function test_tasks_can_be_filtered_by_each_status(): void
    {
        $user = User::factory()->create();

        Task::factory()->pending()->create([
            'user_id' => $user->id,
            'title' => 'Pending Calculus Homework',
            'due_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        Task::factory()->inProgress()->create([
            'user_id' => $user->id,
            'title' => 'In-Progress Physics Lab',
            'due_date' => now()->addDays(5)->format('Y-m-d'),
        ]);

        Task::factory()->completed()->create([
            'user_id' => $user->id,
            'title' => 'Completed Chemistry Quiz',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $overdue = Task::factory()->pending()->create([
            'user_id' => $user->id,
            'title' => 'Overdue History Paper',
            'due_date' => now()->subDays(5)->format('Y-m-d'),
        ]);

        $component = Livewire::actingAs($user)->test(TaskManager::class);

        // Filter Pending
        $component->set('statusFilter', 'pending')
            ->assertSee('Pending Calculus Homework')
            ->assertDontSee('In-Progress Physics Lab')
            ->assertDontSee('Completed Chemistry Quiz');

        // Filter In Progress
        $component->set('statusFilter', 'in_progress')
            ->assertSee('In-Progress Physics Lab')
            ->assertDontSee('Pending Calculus Homework')
            ->assertDontSee('Completed Chemistry Quiz');

        // Filter Completed
        $component->set('statusFilter', 'completed')
            ->assertSee('Completed Chemistry Quiz')
            ->assertDontSee('Pending Calculus Homework')
            ->assertDontSee('In-Progress Physics Lab');

        // Filter Overdue
        $component->set('statusFilter', 'overdue')
            ->assertSee('Overdue History Paper')
            ->assertDontSee('In-Progress Physics Lab')
            ->assertDontSee('Completed Chemistry Quiz');
    }

    public function test_tasks_can_be_filtered_by_each_priority(): void
    {
        $user = User::factory()->create();

        Task::factory()->create([
            'user_id' => $user->id,
            'priority' => 'low',
            'title' => 'Low Priority Reading',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'priority' => 'medium',
            'title' => 'Medium Priority Discussion',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'priority' => 'high',
            'title' => 'High Priority Thesis Defense',
        ]);

        $component = Livewire::actingAs($user)->test(TaskManager::class);

        $component->set('priorityFilter', 'low')
            ->assertSee('Low Priority Reading')
            ->assertDontSee('High Priority Thesis Defense');

        $component->set('priorityFilter', 'medium')
            ->assertSee('Medium Priority Discussion')
            ->assertDontSee('Low Priority Reading');

        $component->set('priorityFilter', 'high')
            ->assertSee('High Priority Thesis Defense')
            ->assertDontSee('Medium Priority Discussion');
    }

    public function test_tasks_can_be_filtered_by_category(): void
    {
        $user = User::factory()->create();

        $algebraCategory = Category::factory()->create(['user_id' => $user->id, 'name' => 'Algebra']);
        $geometryCategory = Category::factory()->create(['user_id' => $user->id, 'name' => 'Geometry']);

        Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $algebraCategory->id,
            'title' => 'Matrix Multiplication Problem Set',
        ]);

        Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $geometryCategory->id,
            'title' => 'Euclidean Proof Assignment',
        ]);

        $component = Livewire::actingAs($user)->test(TaskManager::class);

        $component->set('categoryFilter', (string) $algebraCategory->id)
            ->assertSee('Matrix Multiplication Problem Set')
            ->assertDontSee('Euclidean Proof Assignment');

        $component->set('categoryFilter', (string) $geometryCategory->id)
            ->assertSee('Euclidean Proof Assignment')
            ->assertDontSee('Matrix Multiplication Problem Set');

        $component->set('categoryFilter', 'all')
            ->assertSee('Matrix Multiplication Problem Set')
            ->assertSee('Euclidean Proof Assignment');
    }

    public function test_combined_task_discovery_search_and_filters(): void
    {
        $user = User::factory()->create();

        $csCategory = Category::factory()->create(['user_id' => $user->id, 'name' => 'Computer Science']);
        $artCategory = Category::factory()->create(['user_id' => $user->id, 'name' => 'Art History']);

        // Target match: CS + high priority + in_progress + keyword "Algorithm"
        $targetTask = Task::factory()->inProgress()->create([
            'user_id' => $user->id,
            'category_id' => $csCategory->id,
            'priority' => 'high',
            'title' => 'Sorting Algorithm Benchmark',
            'description' => 'Implement quicksort and mergesort',
        ]);

        // Same category and priority, but pending status
        $otherTask1 = Task::factory()->pending()->create([
            'user_id' => $user->id,
            'category_id' => $csCategory->id,
            'priority' => 'high',
            'title' => 'Algorithm Complexity Quiz',
        ]);

        // Same status and priority, but different category
        $otherTask2 = Task::factory()->inProgress()->create([
            'user_id' => $user->id,
            'category_id' => $artCategory->id,
            'priority' => 'high',
            'title' => 'Renaissance Architecture Review',
        ]);

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('search', 'Algorithm')
            ->set('statusFilter', 'in_progress')
            ->set('priorityFilter', 'high')
            ->set('categoryFilter', (string) $csCategory->id)
            ->assertSee('Sorting Algorithm Benchmark')
            ->assertDontSee('Algorithm Complexity Quiz')
            ->assertDontSee('Renaissance Architecture Review');
    }

    public function test_task_discovery_pagination_and_navigation(): void
    {
        $user = User::factory()->create();

        // Create 15 tasks (TaskManager perPage is 10)
        for ($i = 1; $i <= 15; $i++) {
            Task::factory()->create([
                'user_id' => $user->id,
                'title' => sprintf('Numbered Task %02d', $i),
                'created_at' => now()->subMinutes(16 - $i),
            ]);
        }

        $component = Livewire::actingAs($user)
            ->test(TaskManager::class);

        $tasksOnPage1 = $component->viewData('tasks');
        $this->assertCount(10, $tasksOnPage1);
        $this->assertEquals(15, $tasksOnPage1->total());
        $this->assertTrue($tasksOnPage1->hasPages());

        // Navigate to page 2
        $component->call('gotoPage', 2);
        $tasksOnPage2 = $component->viewData('tasks');
        $this->assertCount(5, $tasksOnPage2);
    }

    public function test_task_discovery_resets_pagination_when_filters_change(): void
    {
        $user = User::factory()->create();

        // Create 15 tasks
        Task::factory()->count(15)->create(['user_id' => $user->id]);

        $component = Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->call('gotoPage', 2);

        $this->assertEquals(2, $component->get('paginators')['page']);

        // Changing search should reset page to 1
        $component->set('search', 'Assignment');
        $this->assertEquals(1, $component->get('paginators')['page']);

        // Navigate to page 2 again
        $component->call('gotoPage', 2);
        $this->assertEquals(2, $component->get('paginators')['page']);

        // Changing statusFilter should reset page to 1
        $component->set('statusFilter', 'pending');
        $this->assertEquals(1, $component->get('paginators')['page']);

        // Navigate to page 2 again
        $component->call('gotoPage', 2);
        $this->assertEquals(2, $component->get('paginators')['page']);

        // Changing priorityFilter should reset page to 1
        $component->set('priorityFilter', 'high');
        $this->assertEquals(1, $component->get('paginators')['page']);

        // Navigate to page 2 again
        $component->call('gotoPage', 2);
        $this->assertEquals(2, $component->get('paginators')['page']);

        // Changing categoryFilter should reset page to 1
        $component->set('categoryFilter', '1');
        $this->assertEquals(1, $component->get('paginators')['page']);
    }

    public function test_task_discovery_filter_reset_helper(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(TaskManager::class)
            ->set('search', 'Final')
            ->set('statusFilter', 'in_progress')
            ->set('priorityFilter', 'high')
            ->set('categoryFilter', '5')
            ->call('resetFilters')
            ->assertSet('search', '')
            ->assertSet('statusFilter', 'all')
            ->assertSet('priorityFilter', 'all')
            ->assertSet('categoryFilter', 'all')
            ->assertSet('sortBy', 'due_date')
            ->assertSet('sortDirection', 'asc');
    }

    public function test_task_discovery_strictly_enforces_user_isolation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $categoryB = Category::factory()->create(['user_id' => $userB->id, 'name' => 'User B Subject']);

        Task::factory()->create([
            'user_id' => $userB->id,
            'category_id' => $categoryB->id,
            'title' => 'Secret Project of User B',
            'description' => 'Confidential information',
        ]);

        // User A searches for User B's task title
        Livewire::actingAs($userA)
            ->test(TaskManager::class)
            ->set('search', 'Secret Project')
            ->assertDontSee('Secret Project of User B')
            ->assertSee('No tasks found');

        // User A tries to filter with User B's category id
        Livewire::actingAs($userA)
            ->test(TaskManager::class)
            ->set('categoryFilter', (string) $categoryB->id)
            ->assertDontSee('Secret Project of User B')
            ->assertSee('No tasks found');
    }
}
