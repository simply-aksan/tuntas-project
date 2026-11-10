<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Category Management — TUNTAS')]
class CategoryManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    /**
     * Curated preset colors for categories
     */
    public const PRESET_COLORS = [
        '#6366f1' => 'Indigo',
        '#3b82f6' => 'Blue',
        '#0284c7' => 'Sky',
        '#0d9488' => 'Teal',
        '#10b981' => 'Emerald',
        '#84cc16' => 'Lime',
        '#f59e0b' => 'Amber',
        '#f97316' => 'Orange',
        '#ef4444' => 'Rose',
        '#ec4899' => 'Pink',
        '#8b5cf6' => 'Purple',
        '#64748b' => 'Slate',
    ];

    /**
     * Search query state
     */
    #[Url(as: 'q')]
    public string $search = '';

    /**
     * Number of categories per page
     */
    public int $perPage = 12;

    /**
     * Modal Visibility Controls
     */
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    /**
     * Form Inputs (for Create & Edit)
     */
    public ?int $categoryIdBeingEdited = null;
    public string $name = '';
    public string $color = '#6366f1';

    /**
     * Deletion tracking state
     */
    public ?int $categoryIdBeingDeleted = null;
    public string $categoryNameBeingDeleted = '';
    public int $categoryTaskCountBeingDeleted = 0;

    /**
     * Notification message banner
     */
    public ?string $feedbackMessage = null;
    public ?string $feedbackType = 'success';

    /**
     * Reset pagination when searching
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Clear search filter
     */
    public function resetSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Validation rules for category creation and updating.
     */
    protected function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                Rule::unique('categories', 'name')
                    ->where(fn ($query) => $query->where('user_id', auth()->id()))
                    ->ignore($this->categoryIdBeingEdited),
            ],
            'color' => [
                'required',
                'string',
                'max:30',
                'regex:/^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/',
            ],
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.min' => 'Category name must be at least 2 characters.',
            'name.max' => 'Category name cannot exceed 50 characters.',
            'name.unique' => 'You already have a category with this name.',
            'color.required' => 'Please select a color for this category.',
            'color.regex' => 'The color must be a valid hex color code (e.g., #6366f1).',
        ];
    }

    /**
     * Select a preset or custom color
     */
    public function selectColor(string $hex): void
    {
        $this->color = $hex;
        $this->resetValidation('color');
    }

    /**
     * Reset the category form properties
     */
    public function resetForm(): void
    {
        $this->reset([
            'categoryIdBeingEdited',
            'name',
            'color',
        ]);
        $this->color = '#6366f1';
        $this->resetValidation();
    }

    /**
     * Open category creation modal
     */
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    /**
     * Close category creation modal
     */
    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    /**
     * Store new category in database
     */
    public function createCategory(): void
    {
        $validated = $this->validate();

        // Enforce authenticated user ownership & Eloquent creation
        auth()->user()->categories()->create([
            'name' => trim($validated['name']),
            'color' => strtolower(trim($validated['color'])),
        ]);

        $this->closeCreateModal();
        $this->notify('Category created successfully!');
    }

    /**
     * Open category edit modal
     */
    public function openEditModal(int $categoryId): void
    {
        $category = Category::findOrFail($categoryId);
        $this->authorize('update', $category);

        $this->resetForm();
        $this->categoryIdBeingEdited = $category->id;
        $this->name = $category->name;
        $this->color = $category->color;

        $this->showEditModal = true;
    }

    /**
     * Close category edit modal
     */
    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    /**
     * Update category in database
     */
    public function updateCategory(): void
    {
        if (!$this->categoryIdBeingEdited) {
            return;
        }

        $category = Category::findOrFail($this->categoryIdBeingEdited);
        $this->authorize('update', $category);

        $validated = $this->validate();

        $category->update([
            'name' => trim($validated['name']),
            'color' => strtolower(trim($validated['color'])),
        ]);

        $this->closeEditModal();
        $this->notify('Category updated successfully!');
    }

    /**
     * Open delete confirmation modal
     */
    public function confirmDelete(int $categoryId): void
    {
        $category = Category::withCount('tasks')->findOrFail($categoryId);
        $this->authorize('delete', $category);

        $this->categoryIdBeingDeleted = $category->id;
        $this->categoryNameBeingDeleted = $category->name;
        $this->categoryTaskCountBeingDeleted = $category->tasks_count;
        $this->showDeleteModal = true;
    }

    /**
     * Close delete confirmation modal
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->categoryIdBeingDeleted = null;
        $this->categoryNameBeingDeleted = '';
        $this->categoryTaskCountBeingDeleted = 0;
    }

    /**
     * Delete category from database
     */
    public function deleteCategory(): void
    {
        if (!$this->categoryIdBeingDeleted) {
            return;
        }

        $category = Category::findOrFail($this->categoryIdBeingDeleted);
        $this->authorize('delete', $category);

        $category->delete();

        $this->closeDeleteModal();
        $this->notify('Category deleted successfully!');
    }

    /**
     * Flash / notify banner helper
     */
    public function notify(string $message, string $type = 'success'): void
    {
        $this->feedbackMessage = $message;
        $this->feedbackType = $type;
    }

    /**
     * Dismiss notification banner
     */
    public function dismissNotification(): void
    {
        $this->feedbackMessage = null;
    }

    /**
     * Render the Livewire component view
     */
    public function render(): View
    {
        $user = auth()->user();

        // Total count and task associations for stats
        $totalCategories = $user->categories()->count();
        $totalCategorizedTasks = $user->tasks()->whereNotNull('category_id')->count();

        // Build categories query strictly scoped to authenticated user
        $categoriesQuery = $user->categories()
            ->withCount('tasks')
            ->when(filled($this->search), function ($query) {
                $search = trim($this->search);
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name');

        $categories = $categoriesQuery->paginate($this->perPage);

        return view('livewire.category-manager', [
            'categories' => $categories,
            'presetColors' => self::PRESET_COLORS,
            'totalCategories' => $totalCategories,
            'totalCategorizedTasks' => $totalCategorizedTasks,
        ]);
    }
}

