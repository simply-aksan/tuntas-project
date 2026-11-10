<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Task Management — TUNTAS')]
class TaskManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    /**
     * Search and Filter Query States
     */
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    #[Url(as: 'priority')]
    public string $priorityFilter = 'all';

    #[Url(as: 'category')]
    public string $categoryFilter = 'all';

    #[Url(as: 'sort')]
    public string $sortBy = 'due_date';

    #[Url(as: 'dir')]
    public string $sortDirection = 'asc';

    public int $perPage = 10;

    /**
     * Modal Visibility Controls
     */
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public bool $showDetailModal = false;
    public bool $showDeleteModal = false;

    /**
     * Form Inputs (for Create & Edit)
     */
    public ?int $taskIdBeingEdited = null;
    public string $title = '';
    public string $description = '';
    public ?int $category_id = null;
    public string $status = 'pending';
    public string $priority = 'medium';
    public ?string $due_date = null;

    /**
     * Active Selected Task for Detail & Delete Modals
     */
    public ?Task $selectedTask = null;
    public ?int $taskIdBeingDeleted = null;
    public string $taskTitleBeingDeleted = '';

    /**
     * Feedback notification message
     */
    public ?string $feedbackMessage = null;
    public ?string $feedbackType = 'success';

    /**
     * Reset pagination when filters change
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSortBy(): void
    {
        $this->resetPage();
    }

    public function updatingSortDirection(): void
    {
        $this->resetPage();
    }

    /**
     * Clear all filters to default
     */
    public function resetFilters(): void
    {
        $this->reset([
            'search',
            'statusFilter',
            'priorityFilter',
            'categoryFilter',
            'sortBy',
            'sortDirection',
        ]);
        $this->sortBy = 'due_date';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    /**
     * Validation rules for task creation and updating.
     */
    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => [
                'nullable',
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->id());
                }),
            ],
            'status' => ['required', Rule::in(['pending', 'in_progress', 'completed'])],
            'priority' => ['required', Rule::in(['low', 'medium', 'high'])],
            'due_date' => ['nullable', 'date_format:Y-m-d'],
        ];
    }

    /**
     * Validation custom error messages
     */
    protected function messages(): array
    {
        return [
            'category_id.exists' => 'The selected category does not belong to your account.',
            'title.required' => 'Task title is required.',
            'due_date.date_format' => 'Please provide a valid deadline date in YYYY-MM-DD format.',
        ];
    }

    /**
     * Reset the task form properties
     */
    public function resetForm(): void
    {
        $this->reset([
            'taskIdBeingEdited',
            'title',
            'description',
            'category_id',
            'status',
            'priority',
            'due_date',
        ]);
        $this->status = 'pending';
        $this->priority = 'medium';
        $this->resetValidation();
    }

    /**
     * Open task creation modal
     */
    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    /**
     * Close task creation modal
     */
    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    /**
     * Store new task in database
     */
    public function createTask(): void
    {
        $validated = $this->validate();

        // Enforce authenticated user ownership & Eloquent creation
        auth()->user()->tasks()->create([
            'category_id' => $validated['category_id'] ?: null,
            'title' => trim($validated['title']),
            'description' => $validated['description'] ? trim($validated['description']) : null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?: null,
        ]);

        $this->closeCreateModal();
        $this->notify('Task created successfully!');
    }

    /**
     * Open task edit modal
     */
    public function openEditModal(int $taskId): void
    {
        $task = Task::findOrFail($taskId);
        $this->authorize('update', $task);

        $this->resetForm();
        $this->taskIdBeingEdited = $task->id;
        $this->title = $task->title;
        $this->description = $task->description ?? '';
        $this->category_id = $task->category_id;
        $this->status = $task->status;
        $this->priority = $task->priority;
        $this->due_date = $task->due_date ? $task->due_date->format('Y-m-d') : null;

        $this->showEditModal = true;
    }

    /**
     * Close task edit modal
     */
    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    /**
     * Update task in database
     */
    public function updateTask(): void
    {
        if (!$this->taskIdBeingEdited) {
            return;
        }

        $task = Task::findOrFail($this->taskIdBeingEdited);
        $this->authorize('update', $task);

        $validated = $this->validate();

        $task->update([
            'category_id' => $validated['category_id'] ?: null,
            'title' => trim($validated['title']),
            'description' => $validated['description'] ? trim($validated['description']) : null,
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'due_date' => $validated['due_date'] ?: null,
        ]);

        $this->closeEditModal();

        // If the task detail modal is open for this task, refresh it
        if ($this->selectedTask && $this->selectedTask->id === $task->id) {
            $this->selectedTask = $task->fresh(['category']);
        }

        $this->notify('Task updated successfully!');
    }

    /**
     * Open task detail modal
     */
    public function openDetailModal(int $taskId): void
    {
        $task = Task::with('category')->findOrFail($taskId);
        $this->authorize('view', $task);

        $this->selectedTask = $task;
        $this->showDetailModal = true;
    }

    /**
     * Close task detail modal
     */
    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedTask = null;
    }

    /**
     * Open delete confirmation modal
     */
    public function confirmDelete(int $taskId): void
    {
        $task = Task::findOrFail($taskId);
        $this->authorize('delete', $task);

        $this->taskIdBeingDeleted = $task->id;
        $this->taskTitleBeingDeleted = $task->title;
        $this->showDeleteModal = true;
    }

    /**
     * Close delete confirmation modal
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->taskIdBeingDeleted = null;
        $this->taskTitleBeingDeleted = '';
    }

    /**
     * Delete task from database
     */
    public function deleteTask(): void
    {
        if (!$this->taskIdBeingDeleted) {
            return;
        }

        $task = Task::findOrFail($this->taskIdBeingDeleted);
        $this->authorize('delete', $task);

        $task->delete();

        // If the task detail modal is open for this task, close it
        if ($this->selectedTask && $this->selectedTask->id === $this->taskIdBeingDeleted) {
            $this->closeDetailModal();
        }

        $this->closeDeleteModal();
        $this->notify('Task deleted successfully!');
    }

    /**
     * Quick status update from task card or detail modal
     */
    public function updateStatus(int $taskId, string $newStatus): void
    {
        if (!in_array($newStatus, ['pending', 'in_progress', 'completed'], true)) {
            return;
        }

        $task = Task::findOrFail($taskId);
        $this->authorize('update', $task);

        $task->update(['status' => $newStatus]);

        if ($this->selectedTask && $this->selectedTask->id === $task->id) {
            $this->selectedTask = $task->fresh(['category']);
        }

        $label = match ($newStatus) {
            'completed' => 'Completed',
            'in_progress' => 'In Progress',
            default => 'Pending',
        };

        $this->notify("Task status updated to {$label}.");
    }

    /**
     * Quick priority update from task card or detail modal
     */
    public function updatePriority(int $taskId, string $newPriority): void
    {
        if (!in_array($newPriority, ['low', 'medium', 'high'], true)) {
            return;
        }

        $task = Task::findOrFail($taskId);
        $this->authorize('update', $task);

        $task->update(['priority' => $newPriority]);

        if ($this->selectedTask && $this->selectedTask->id === $task->id) {
            $this->selectedTask = $task->fresh(['category']);
        }

        $label = ucfirst($newPriority);
        $this->notify("Task priority updated to {$label}.");
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
     * Toggle sort column and direction
     */
    public function toggleSort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Render the Livewire component view
     */
    public function render(): View
    {
        $user = auth()->user();

        // Status counts for top summary / quick filter tabs
        $taskCounts = [
            'all' => $user->tasks()->count(),
            'pending' => $user->tasks()->pending()->count(),
            'in_progress' => $user->tasks()->inProgress()->count(),
            'completed' => $user->tasks()->completed()->count(),
            'overdue' => $user->tasks()->overdue()->count(),
        ];

        // Fetch user categories for dropdown filter & create/edit forms
        $categories = $user->categories()->orderBy('name')->get();

        // Build filtered tasks query strictly scoped to authenticated user
        $tasksQuery = $user->tasks()
            ->with('category')
            ->when(filled($this->search), function ($query) {
                $search = trim($this->search);
                $query->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($this->statusFilter === 'overdue', function ($query) {
                $query->overdue();
            })
            ->when(in_array($this->statusFilter, ['pending', 'in_progress', 'completed'], true), function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when(in_array($this->priorityFilter, ['low', 'medium', 'high'], true), function ($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->when(is_numeric($this->categoryFilter) && (int) $this->categoryFilter > 0, function ($query) {
                $query->where('category_id', (int) $this->categoryFilter);
            });

        // Apply sorting
        if ($this->sortBy === 'due_date') {
            // Null due dates appear last regardless of asc/desc
            $dir = strtoupper($this->sortDirection) === 'DESC' ? 'DESC' : 'ASC';
            $tasksQuery->orderByRaw("due_date IS NULL, due_date {$dir}");
        } elseif (in_array($this->sortBy, ['title', 'status', 'priority', 'created_at'], true)) {
            $tasksQuery->orderBy($this->sortBy, $this->sortDirection);
        } else {
            $tasksQuery->latest();
        }

        $tasks = $tasksQuery->paginate($this->perPage);

        return view('livewire.task-manager', [
            'tasks' => $tasks,
            'categories' => $categories,
            'taskCounts' => $taskCounts,
        ]);
    }
}
