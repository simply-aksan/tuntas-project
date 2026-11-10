<?php

namespace Tests\Feature;

use App\Livewire\CategoryManager;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_categories_page(): void
    {
        $response = $this->get('/categories');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_categories_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/categories');

        $response->assertStatus(200);
        $response->assertSeeLivewire(CategoryManager::class);
    }

    public function test_category_list_only_displays_authenticated_user_categories(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $categoryA = Category::factory()->create([
            'user_id' => $userA->id,
            'name' => 'Computer Science A',
        ]);

        $categoryB = Category::factory()->create([
            'user_id' => $userB->id,
            'name' => 'Secret Subject B',
        ]);

        Livewire::actingAs($userA)
            ->test(CategoryManager::class)
            ->assertSee('Computer Science A')
            ->assertDontSee('Secret Subject B');
    }

    public function test_category_list_displays_task_counts(): void
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Physics 101',
        ]);

        Task::factory()->count(3)->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->assertSee('Physics 101')
            ->assertSee('3 tasks');
    }

    public function test_categories_can_be_searched_by_name(): void
    {
        $user = User::factory()->create();

        Category::factory()->create(['user_id' => $user->id, 'name' => 'Calculus II']);
        Category::factory()->create(['user_id' => $user->id, 'name' => 'Organic Chemistry']);

        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->set('search', 'Calculus')
            ->assertSee('Calculus II')
            ->assertDontSee('Organic Chemistry')
            ->set('search', 'Organic')
            ->assertSee('Organic Chemistry')
            ->assertDontSee('Calculus II');
    }

    public function test_user_can_create_category_with_valid_data(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->call('openCreateModal')
            ->assertSet('showCreateModal', true)
            ->set('name', 'Linear Algebra')
            ->set('color', '#3b82f6')
            ->call('createCategory')
            ->assertSet('showCreateModal', false)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'Linear Algebra',
            'color' => '#3b82f6',
        ]);
    }

    public function test_category_creation_validation_rules(): void
    {
        $user = User::factory()->create();

        Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Existing Subject',
        ]);

        // Empty name and invalid color
        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->set('name', '')
            ->set('color', 'not-a-color')
            ->call('createCategory')
            ->assertHasErrors(['name', 'color']);

        // Name too short
        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->set('name', 'A')
            ->set('color', '#10b981')
            ->call('createCategory')
            ->assertHasErrors(['name']);

        // Duplicate name for same user
        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->set('name', 'Existing Subject')
            ->set('color', '#10b981')
            ->call('createCategory')
            ->assertHasErrors(['name']);
    }

    public function test_different_users_can_have_categories_with_same_name(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        Category::factory()->create([
            'user_id' => $userA->id,
            'name' => 'English Literature',
        ]);

        Livewire::actingAs($userB)
            ->test(CategoryManager::class)
            ->set('name', 'English Literature')
            ->set('color', '#8b5cf6')
            ->call('createCategory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'user_id' => $userB->id,
            'name' => 'English Literature',
        ]);
    }

    public function test_category_color_selection(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->call('openCreateModal')
            ->call('selectColor', '#f59e0b')
            ->assertSet('color', '#f59e0b')
            ->set('name', 'History 101')
            ->call('createCategory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'user_id' => $user->id,
            'name' => 'History 101',
            'color' => '#f59e0b',
        ]);
    }

    public function test_user_can_edit_and_update_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Old Category Name',
            'color' => '#6366f1',
        ]);

        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->call('openEditModal', $category->id)
            ->assertSet('showEditModal', true)
            ->assertSet('name', 'Old Category Name')
            ->assertSet('color', '#6366f1')
            ->set('name', 'Updated Category Name')
            ->set('color', '#ec4899')
            ->call('updateCategory')
            ->assertSet('showEditModal', false)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category Name',
            'color' => '#ec4899',
        ]);
    }

    public function test_category_update_ignores_current_category_name_uniqueness(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Economics 101',
            'color' => '#6366f1',
        ]);

        // Keep same name, update color only
        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->call('openEditModal', $category->id)
            ->set('color', '#0d9488')
            ->call('updateCategory')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Economics 101',
            'color' => '#0d9488',
        ]);
    }

    public function test_user_cannot_update_category_of_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $categoryB = Category::factory()->create([
            'user_id' => $userB->id,
            'name' => 'User B Subject',
        ]);

        Livewire::actingAs($userA)
            ->test(CategoryManager::class)
            ->call('openEditModal', $categoryB->id)
            ->assertForbidden();
    }

    public function test_user_can_delete_category(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Category To Delete',
        ]);

        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->call('confirmDelete', $category->id)
            ->assertSet('showDeleteModal', true)
            ->assertSet('categoryIdBeingDeleted', $category->id)
            ->call('deleteCategory')
            ->assertSet('showDeleteModal', false);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_user_cannot_delete_category_of_another_user(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $categoryB = Category::factory()->create([
            'user_id' => $userB->id,
            'name' => 'Protected Category B',
        ]);

        Livewire::actingAs($userA)
            ->test(CategoryManager::class)
            ->call('confirmDelete', $categoryB->id)
            ->assertForbidden();

        $this->assertDatabaseHas('categories', [
            'id' => $categoryB->id,
        ]);
    }

    public function test_deleting_category_sets_assigned_tasks_category_id_to_null(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create(['user_id' => $user->id]);

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Assigned Task',
        ]);

        $this->assertEquals($category->id, $task->fresh()->category_id);

        Livewire::actingAs($user)
            ->test(CategoryManager::class)
            ->call('confirmDelete', $category->id)
            ->call('deleteCategory');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'category_id' => null,
        ]);
    }

    public function test_category_pagination_works_properly(): void
    {
        $user = User::factory()->create();

        // Create 15 categories (perPage is 12)
        Category::factory()->count(15)->create([
            'user_id' => $user->id,
        ]);

        $component = Livewire::actingAs($user)
            ->test(CategoryManager::class);

        $categoriesOnPage1 = $component->viewData('categories');
        $this->assertCount(12, $categoriesOnPage1);
        $this->assertEquals(15, $categoriesOnPage1->total());
        $this->assertTrue($categoriesOnPage1->hasPages());

        $component->call('gotoPage', 2);
        $categoriesOnPage2 = $component->viewData('categories');
        $this->assertCount(3, $categoriesOnPage2);
    }
}

