<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Welcome Header & Quick Action Banner -->
    <div class="rounded-xl border border-slate-200/80 bg-white p-6 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">
                    Welcome back, {{ auth()->user()->name }}
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Here is an overview of your academic and personal task progress.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a 
                    href="{{ route('tasks.index') }}" 
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xs hover:bg-indigo-700 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Manage Tasks</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Real-time Statistic Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Tasks -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Tasks</span>
                <span class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">{{ $totalTasks }}</span>
                <span class="text-xs text-slate-400">assigned</span>
            </div>
        </div>

        <!-- In Progress -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">In Progress</span>
                <span class="p-2 rounded-lg bg-blue-50 text-blue-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight text-slate-900">{{ $inProgressTasks }}</span>
                <span class="text-xs text-slate-400">active</span>
            </div>
        </div>

        <!-- Overdue -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-rose-500 uppercase tracking-wider">Overdue</span>
                <span class="p-2 rounded-lg bg-rose-50 text-rose-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline gap-2">
                <span class="text-2xl font-bold tracking-tight {{ $overdueTasks > 0 ? 'text-rose-600' : 'text-slate-900' }}">
                    {{ $overdueTasks }}
                </span>
                <span class="text-xs text-slate-400">attention</span>
            </div>
        </div>

        <!-- Completed Rate -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Completed</span>
                <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <span class="text-2xl font-bold tracking-tight text-slate-900">{{ $completedTasks }}</span>
                <span class="text-xs font-semibold text-emerald-600">{{ $completionRate }}% completed</span>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Two-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming & Critical Tasks (Left 2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Overdue Tasks Alert Section if any exist -->
            @if($overdueTaskList->count() > 0)
                <div class="rounded-xl border border-rose-200 bg-rose-50/50 p-5 shadow-xs">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2 text-rose-700 font-semibold text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span>Action Required: Overdue Tasks</span>
                        </div>
                        <a href="{{ route('tasks.index', ['status' => 'overdue']) }}" class="text-xs font-medium text-rose-700 hover:text-rose-800 underline">
                            View All ({{ $overdueTasks }})
                        </a>
                    </div>

                    <div class="space-y-2">
                        @foreach($overdueTaskList as $task)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-white border border-rose-200/80 shadow-2xs">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $task->title }}</p>
                                    <div class="flex items-center gap-2 mt-0.5 text-xs text-rose-600 font-medium">
                                        <span>Due: {{ $task->due_date->format('M d, Y') }}</span>
                                        @if($task->category)
                                            <span class="text-slate-300">&bull;</span>
                                            <span style="color: {{ $task->category->color }}">{{ $task->category->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                <button 
                                    wire:click="toggleComplete({{ $task->id }})" 
                                    class="shrink-0 ml-3 px-2.5 py-1 text-xs font-medium rounded-md bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100 transition-colors"
                                >
                                    Mark Done
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Upcoming Tasks Card -->
            <div class="rounded-xl border border-slate-200/80 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-slate-900">Upcoming Deadlines</h3>
                    <a href="{{ route('tasks.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                        View All Tasks &rarr;
                    </a>
                </div>

                @if($upcomingTasks->isEmpty())
                    <div class="py-8 text-center">
                        <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm font-medium text-slate-600">No pending deadlines right now</p>
                        <p class="text-xs text-slate-400 mt-0.5">You're completely on track with your tasks.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($upcomingTasks as $task)
                            <div class="py-3.5 flex items-center justify-between gap-3 first:pt-0 last:pb-0">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold text-slate-900 truncate">{{ $task->title }}</p>
                                        @if($task->priority === 'high')
                                            <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">High</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                        <span>Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No date' }}</span>
                                        @if($task->category)
                                            <span class="text-slate-300">&bull;</span>
                                            <span class="flex items-center gap-1">
                                                <span class="w-2 h-2 rounded-full" style="background-color: {{ $task->category->color }}"></span>
                                                <span class="text-slate-600">{{ $task->category->name }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <button 
                                    wire:click="toggleComplete({{ $task->id }})" 
                                    class="shrink-0 p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                    title="Mark completed"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Categories & Academic Context (Right Column) -->
        <div class="space-y-6">
            <!-- Category Breakdown Card -->
            <div class="rounded-xl border border-slate-200/80 bg-white p-6 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-base font-semibold text-slate-900">Your Categories</h3>
                    <a href="{{ route('categories.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">
                        Manage &rarr;
                    </a>
                </div>

                @if($categoriesBreakdown->isEmpty())
                    <div class="py-6 text-center">
                        <p class="text-sm text-slate-500">No categories created yet.</p>
                        <a href="{{ route('categories.index') }}" class="mt-2 inline-flex text-xs font-semibold text-indigo-600 hover:text-indigo-700">
                            Create First Category
                        </a>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($categoriesBreakdown as $cat)
                            <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/50">
                                <div class="flex items-center justify-between mb-1.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background-color: {{ $cat->color }}"></span>
                                        <span class="text-xs font-semibold text-slate-800 truncate">{{ $cat->name }}</span>
                                    </div>
                                    <span class="text-xs text-slate-500 font-medium">
                                        {{ $cat->tasks_count }} {{ Str::plural('task', $cat->tasks_count) }}
                                    </span>
                                </div>
                                <!-- Progress bar for completed vs total in category -->
                                @php
                                    $catPercent = $cat->tasks_count > 0 ? (int) round(($cat->completed_tasks_count / $cat->tasks_count) * 100) : 0;
                                @endphp
                                <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full transition-all duration-300" style="width: {{ $catPercent }}%; background-color: {{ $cat->color }};"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Quick Info Card -->
            <div class="rounded-xl border border-slate-200/80 bg-gradient-to-br from-indigo-500 to-indigo-600 p-5 text-white shadow-xs">
                <h4 class="font-bold text-sm tracking-tight">TUNTAS Academic Flow</h4>
                <p class="mt-1 text-xs text-indigo-100 leading-relaxed">
                    Prioritize tasks based on high urgency and overdue dates to keep your coursework stress-free.
                </p>
                <div class="mt-4 pt-3 border-t border-indigo-400/40 flex items-center justify-between text-xs text-indigo-100">
                    <span>Active Categories: {{ $totalCategories }}</span>
                    <span>Pending: {{ $pendingTasks }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
