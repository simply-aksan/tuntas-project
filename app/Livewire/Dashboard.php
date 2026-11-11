<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard — TUNTAS')]
class Dashboard extends Component
{
    /**
     * Mark a task as completed directly from the dashboard widget.
     */
    public function toggleComplete(int $taskId): void
    {
        $task = Auth::user()->tasks()->find($taskId);

        if (!$task) {
            return;
        }

        if ($task->status === 'completed') {
            $task->update(['status' => 'pending']);
        } else {
            $task->update(['status' => 'completed']);
        }
    }

    public function render(): View
    {
        $user = Auth::user();

        // Real aggregated statistics for the authenticated user only
        $totalTasks = $user->tasks()->count();
        $pendingTasks = $user->tasks()->pending()->count();
        $inProgressTasks = $user->tasks()->inProgress()->count();
        $completedTasks = $user->tasks()->completed()->count();
        $overdueTasks = $user->tasks()->overdue()->count();
        $totalCategories = $user->categories()->count();

        // Completion rate calculation
        $completionRate = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        // Upcoming due tasks (next 5 uncompleted tasks ordered by due date)
        $upcomingTasks = $user->tasks()
            ->with('category')
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Overdue tasks list (top 5 overdue tasks)
        $overdueTaskList = $user->tasks()
            ->with('category')
            ->overdue()
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        // Category breakdown with task counts
        $categoriesBreakdown = $user->categories()
            ->withCount(['tasks', 'tasks as completed_tasks_count' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('tasks_count', 'desc')
            ->limit(6)
            ->get();

        return view('livewire.dashboard', [
            'totalTasks' => $totalTasks,
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'completedTasks' => $completedTasks,
            'overdueTasks' => $overdueTasks,
            'totalCategories' => $totalCategories,
            'completionRate' => $completionRate,
            'upcomingTasks' => $upcomingTasks,
            'overdueTaskList' => $overdueTaskList,
            'categoriesBreakdown' => $categoriesBreakdown,
        ]);
    }
}
