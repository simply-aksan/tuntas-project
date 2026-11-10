<div>
    @section('header', 'Task Management')

    <div class="space-y-6 max-w-7xl mx-auto">
        <!-- Notification Alert -->
        @if ($feedbackMessage)
            <div 
                x-data="{ show: true }" 
                x-show="show" 
                x-transition 
                class="rounded-xl border p-4 flex items-center justify-between gap-3 shadow-xs {{ $feedbackType === 'error' ? 'bg-rose-50 border-rose-200 text-rose-800' : 'bg-emerald-50 border-emerald-200 text-emerald-800' }}"
            >
                <div class="flex items-center gap-2.5">
                    @if ($feedbackType === 'error')
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @else
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endif
                    <p class="text-sm font-medium">{{ $feedbackMessage }}</p>
                </div>
                <button 
                    type="button" 
                    wire:click="dismissNotification"
                    class="p-1 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-200/50 transition-colors"
                    aria-label="Dismiss"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Summary & Quick Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
            <!-- All Tasks -->
            <button 
                type="button"
                wire:click="$set('statusFilter', 'all')"
                class="rounded-xl border p-4 text-left transition-all duration-150 {{ $statusFilter === 'all' ? 'bg-indigo-50/60 border-indigo-200 shadow-xs ring-1 ring-indigo-200' : 'bg-white border-slate-200/80 hover:border-slate-300 shadow-xs' }}"
            >
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Total Tasks</span>
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                </div>
                <div class="mt-2 text-2xl font-bold text-slate-900">{{ $taskCounts['all'] }}</div>
                <p class="mt-0.5 text-xs text-slate-500">All registered tasks</p>
            </button>

            <!-- Pending -->
            <button 
                type="button"
                wire:click="$set('statusFilter', 'pending')"
                class="rounded-xl border p-4 text-left transition-all duration-150 {{ $statusFilter === 'pending' ? 'bg-slate-100 border-slate-300 shadow-xs ring-1 ring-slate-300' : 'bg-white border-slate-200/80 hover:border-slate-300 shadow-xs' }}"
            >
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Pending</span>
                    <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                </div>
                <div class="mt-2 text-2xl font-bold text-slate-800">{{ $taskCounts['pending'] }}</div>
                <p class="mt-0.5 text-xs text-slate-500">Not started yet</p>
            </button>

            <!-- In Progress -->
            <button 
                type="button"
                wire:click="$set('statusFilter', 'in_progress')"
                class="rounded-xl border p-4 text-left transition-all duration-150 {{ $statusFilter === 'in_progress' ? 'bg-blue-50/70 border-blue-200 shadow-xs ring-1 ring-blue-200' : 'bg-white border-slate-200/80 hover:border-slate-300 shadow-xs' }}"
            >
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-blue-600">
                    <span>In Progress</span>
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                </div>
                <div class="mt-2 text-2xl font-bold text-blue-700">{{ $taskCounts['in_progress'] }}</div>
                <p class="mt-0.5 text-xs text-slate-500">Currently active</p>
            </button>

            <!-- Completed -->
            <button 
                type="button"
                wire:click="$set('statusFilter', 'completed')"
                class="rounded-xl border p-4 text-left transition-all duration-150 {{ $statusFilter === 'completed' ? 'bg-emerald-50/70 border-emerald-200 shadow-xs ring-1 ring-emerald-200' : 'bg-white border-slate-200/80 hover:border-slate-300 shadow-xs' }}"
            >
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-emerald-600">
                    <span>Completed</span>
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-2 text-2xl font-bold text-emerald-700">{{ $taskCounts['completed'] }}</div>
                <p class="mt-0.5 text-xs text-slate-500">Finished tasks</p>
            </button>

            <!-- Overdue -->
            <button 
                type="button"
                wire:click="$set('statusFilter', 'overdue')"
                class="col-span-2 sm:col-span-1 rounded-xl border p-4 text-left transition-all duration-150 {{ $statusFilter === 'overdue' ? 'bg-rose-50/70 border-rose-200 shadow-xs ring-1 ring-rose-200' : 'bg-white border-slate-200/80 hover:border-slate-300 shadow-xs' }}"
            >
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-rose-600">
                    <span>Overdue</span>
                    <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                </div>
                <div class="mt-2 text-2xl font-bold text-rose-700">{{ $taskCounts['overdue'] }}</div>
                <p class="mt-0.5 text-xs text-slate-500">Needs immediate attention</p>
            </button>
        </div>

        <!-- Action Bar: Search, Filters, and New Task Button -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[240px]">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search tasks by title or description..." 
                        class="w-full pl-10 pr-4 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50/50 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors"
                    >
                    @if ($search)
                        <button 
                            type="button" 
                            wire:click="$set('search', '')"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>

                <!-- Dropdown Filters & Actions -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Category Filter -->
                    <select 
                        wire:model.live="categoryFilter"
                        class="text-xs sm:text-sm font-medium border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 hover:border-slate-300 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors"
                    >
                        <option value="all">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <!-- Priority Filter -->
                    <select 
                        wire:model.live="priorityFilter"
                        class="text-xs sm:text-sm font-medium border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 hover:border-slate-300 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors"
                    >
                        <option value="all">All Priorities</option>
                        <option value="high">High Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="low">Low Priority</option>
                    </select>

                    <!-- Sort By -->
                    <select 
                        wire:model.live="sortBy"
                        class="text-xs sm:text-sm font-medium border border-slate-200 rounded-lg px-3 py-2 bg-white text-slate-700 hover:border-slate-300 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors"
                    >
                        <option value="due_date">Sort by Deadline</option>
                        <option value="priority">Sort by Priority</option>
                        <option value="created_at">Sort by Created Date</option>
                        <option value="title">Sort by Title</option>
                    </select>

                    <!-- Sort Direction Toggle -->
                    <button 
                        type="button" 
                        wire:click="toggleSort('{{ $sortBy }}')" 
                        title="Toggle sort direction ({{ $sortDirection === 'asc' ? 'Ascending' : 'Descending' }})"
                        class="p-2 border border-slate-200 rounded-lg bg-white text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-colors cursor-pointer"
                    >
                        @if ($sortDirection === 'asc')
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                            </svg>
                        @endif
                    </button>

                    <!-- Reset Filters Button (if active) -->
                    @if ($search || $statusFilter !== 'all' || $priorityFilter !== 'all' || $categoryFilter !== 'all' || $sortBy !== 'due_date' || $sortDirection !== 'asc')
                        <button 
                            type="button" 
                            wire:click="resetFilters" 
                            title="Reset all filters"
                            class="px-2.5 py-2 text-xs font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                        >
                            Reset
                        </button>
                    @endif

                    <!-- Create Task Trigger -->
                    <button 
                        type="button" 
                        wire:click="openCreateModal"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-xs transition-colors cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>New Task</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Task List Container -->
        <div class="rounded-xl border border-slate-200/80 bg-white shadow-xs overflow-hidden">
            @if ($tasks->isEmpty())
                <!-- Clean Empty State -->
                <div class="p-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 mb-4">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">No tasks found</h3>
                    <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">
                        @if ($search || $statusFilter !== 'all' || $priorityFilter !== 'all' || $categoryFilter !== 'all')
                            No tasks matched your active filter criteria. Try resetting or adjusting your search.
                        @else
                            You haven't created any tasks yet. Stay organized by adding your first academic task!
                        @endif
                    </p>
                    <div class="mt-6 flex items-center justify-center gap-3">
                        @if ($search || $statusFilter !== 'all' || $priorityFilter !== 'all' || $categoryFilter !== 'all')
                            <button 
                                type="button" 
                                wire:click="resetFilters" 
                                class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors"
                            >
                                Clear Filters
                            </button>
                        @endif
                        <button 
                            type="button" 
                            wire:click="openCreateModal"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-xs transition-colors"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Create Your First Task</span>
                        </button>
                    </div>
                </div>
            @else
                <!-- Tasks List Table / Rows -->
                <div class="divide-y divide-slate-100">
                    @foreach ($tasks as $task)
                        <div 
                            wire:key="task-row-{{ $task->id }}"
                            class="p-4 sm:p-5 hover:bg-slate-50/70 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4 {{ $task->status === 'completed' ? 'opacity-75 bg-slate-50/30' : '' }}"
                        >
                            <!-- Left: Status Checkbox & Main Info -->
                            <div class="flex items-start gap-3.5 min-w-0 flex-1">
                                <!-- Status Toggle Action -->
                                <button 
                                    type="button" 
                                    wire:click="updateStatus({{ $task->id }}, '{{ $task->status === 'completed' ? 'pending' : 'completed' }}')"
                                    title="Mark as {{ $task->status === 'completed' ? 'pending' : 'completed' }}"
                                    class="mt-1 h-5 w-5 rounded-md border flex items-center justify-center shrink-0 transition-colors cursor-pointer {{ $task->status === 'completed' ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-300 hover:border-indigo-600 hover:bg-indigo-50' }}"
                                >
                                    @if ($task->status === 'completed')
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </button>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <!-- Task Title -->
                                        <button 
                                            type="button" 
                                            wire:click="openDetailModal({{ $task->id }})"
                                            class="text-left font-semibold text-sm sm:text-base text-slate-900 hover:text-indigo-600 transition-colors cursor-pointer {{ $task->status === 'completed' ? 'line-through text-slate-500' : '' }}"
                                        >
                                            {{ $task->title }}
                                        </button>

                                        <!-- Category Badge -->
                                        @if ($task->category)
                                            <span 
                                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium"
                                                style="background-color: {{ $task->category->color }}15; color: {{ $task->category->color }}; border: 1px solid {{ $task->category->color }}30;"
                                            >
                                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task->category->color }};"></span>
                                                {{ $task->category->name }}
                                            </span>
                                        @endif

                                        <!-- Priority Badge -->
                                        @if ($task->priority === 'high')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                                High
                                            </span>
                                        @elseif ($task->priority === 'medium')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                                Medium
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                Low
                                            </span>
                                        @endif

                                        <!-- Status Badge -->
                                        @if ($task->status === 'completed')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3 h-3 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Completed
                                            </span>
                                        @elseif ($task->status === 'in_progress')
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                                In Progress
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                Pending
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Description Preview (if present) -->
                                    @if ($task->description)
                                        <p class="mt-1 text-xs text-slate-500 line-clamp-1">
                                            {{ $task->description }}
                                        </p>
                                    @endif

                                    <!-- Deadline & Timestamps Information -->
                                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                        @if ($task->due_date)
                                            <div class="flex items-center gap-1.5 {{ $task->isOverdue() ? 'text-rose-600 font-semibold' : ($task->due_date->isToday() ? 'text-amber-600 font-medium' : 'text-slate-600') }}">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>
                                                    @if ($task->isOverdue())
                                                        Overdue ({{ $task->due_date->format('M d, Y') }})
                                                    @elseif ($task->due_date->isToday())
                                                        Due today
                                                    @elseif ($task->due_date->isTomorrow())
                                                        Due tomorrow
                                                    @else
                                                        Due {{ $task->due_date->format('M d, Y') }}
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-slate-400">No deadline set</span>
                                        @endif

                                        <span class="text-slate-300">&bull;</span>
                                        <span>Created {{ $task->created_at->diffForHumans() }}</span>

                                        @if ($task->completed_at)
                                            <span class="text-slate-300">&bull;</span>
                                            <span class="text-emerald-700">Completed {{ $task->completed_at->format('M d, H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Quick Management & Action Buttons -->
                            <div class="flex items-center justify-end gap-1.5 sm:self-center shrink-0">
                                <!-- Status Dropdown -->
                                <select 
                                    wire:change="updateStatus({{ $task->id }}, $event.target.value)"
                                    class="text-xs font-medium border border-slate-200 rounded-lg px-2.5 py-1.5 bg-white text-slate-700 hover:border-slate-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 transition-colors"
                                >
                                    <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>

                                <!-- Priority Dropdown -->
                                <select 
                                    wire:change="updatePriority({{ $task->id }}, $event.target.value)"
                                    class="text-xs font-medium border border-slate-200 rounded-lg px-2.5 py-1.5 bg-white text-slate-700 hover:border-slate-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-200 transition-colors"
                                >
                                    <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>High</option>
                                </select>

                                <!-- View Detail Button -->
                                <button 
                                    type="button" 
                                    wire:click="openDetailModal({{ $task->id }})"
                                    title="View task details"
                                    class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                <!-- Edit Button -->
                                <button 
                                    type="button" 
                                    wire:click="openEditModal({{ $task->id }})"
                                    title="Edit task"
                                    class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <button 
                                    type="button" 
                                    wire:click="confirmDelete({{ $task->id }})"
                                    title="Delete task"
                                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Footer -->
                @if ($tasks->hasPages())
                    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50">
                        {{ $tasks->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL: CREATE TASK                         -->
    <!-- ========================================== -->
    @if ($showCreateModal)
        <div 
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            <!-- Backdrop -->
            <div 
                wire:click="closeCreateModal"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                    @click.outside="$wire.closeCreateModal()"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900" id="modal-title">Create New Task</h3>
                        </div>
                        <button 
                            type="button" 
                            wire:click="closeCreateModal"
                            class="rounded-lg p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form wire:submit="createTask">
                        <div class="space-y-4 px-6 py-5">
                            <!-- Title Field -->
                            <div>
                                <label for="task-title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Task Title <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="task-title"
                                    wire:model="title" 
                                    placeholder="e.g. Complete Calculus Assignment 3" 
                                    class="w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('title') border-rose-300 ring-2 ring-rose-100 @enderror"
                                >
                                @error('title')
                                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category & Priority Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Category -->
                                <div>
                                    <label for="task-category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Category
                                    </label>
                                    <select 
                                        id="task-category"
                                        wire:model="category_id"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('category_id') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    >
                                        <option value="">No Category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Priority -->
                                <div>
                                    <label for="task-priority" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Priority <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        id="task-priority"
                                        wire:model="priority"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('priority') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    >
                                        <option value="low">Low Priority</option>
                                        <option value="medium">Medium Priority</option>
                                        <option value="high">High Priority</option>
                                    </select>
                                    @error('priority')
                                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deadline & Initial Status Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Deadline Date -->
                                <div>
                                    <label for="task-deadline" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Deadline (Due Date)
                                    </label>
                                    <input 
                                        type="date" 
                                        id="task-deadline"
                                        wire:model="due_date"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('due_date') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    >
                                    @error('due_date')
                                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Initial Status -->
                                <div>
                                    <label for="task-status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Initial Status <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        id="task-status"
                                        wire:model="status"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('status') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description Field -->
                            <div>
                                <label for="task-description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Description / Notes
                                </label>
                                <textarea 
                                    id="task-description"
                                    rows="3" 
                                    wire:model="description"
                                    placeholder="Add details, instructions, references or subtasks..." 
                                    class="w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('description') border-rose-300 ring-2 ring-rose-100 @enderror"
                                ></textarea>
                                @error('description')
                                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Modal Actions Footer -->
                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-3">
                            <button 
                                type="button" 
                                wire:click="closeCreateModal"
                                class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow-xs transition-colors cursor-pointer"
                            >
                                <span wire:loading.remove wire:target="createTask">Create Task</span>
                                <span wire:loading wire:target="createTask">Creating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- MODAL: EDIT TASK                           -->
    <!-- ========================================== -->
    @if ($showEditModal)
        <div 
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-edit-title" 
            role="dialog" 
            aria-modal="true"
        >
            <!-- Backdrop -->
            <div 
                wire:click="closeEditModal"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                    @click.outside="$wire.closeEditModal()"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-slate-900" id="modal-edit-title">Edit Task</h3>
                        </div>
                        <button 
                            type="button" 
                            wire:click="closeEditModal"
                            class="rounded-lg p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form wire:submit="updateTask">
                        <div class="space-y-4 px-6 py-5">
                            <!-- Title Field -->
                            <div>
                                <label for="edit-task-title" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Task Title <span class="text-rose-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="edit-task-title"
                                    wire:model="title" 
                                    class="w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('title') border-rose-300 ring-2 ring-rose-100 @enderror"
                                >
                                @error('title')
                                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category & Priority Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Category -->
                                <div>
                                    <label for="edit-task-category" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Category
                                    </label>
                                    <select 
                                        id="edit-task-category"
                                        wire:model="category_id"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('category_id') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    >
                                        <option value="">No Category</option>
                                        @foreach ($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Priority -->
                                <div>
                                    <label for="edit-task-priority" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Priority <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        id="edit-task-priority"
                                        wire:model="priority"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('priority') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    >
                                        <option value="low">Low Priority</option>
                                        <option value="medium">Medium Priority</option>
                                        <option value="high">High Priority</option>
                                    </select>
                                    @error('priority')
                                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Deadline & Status Row -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Deadline Date -->
                                <div>
                                    <label for="edit-task-deadline" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Deadline (Due Date)
                                    </label>
                                    <input 
                                        type="date" 
                                        id="edit-task-deadline"
                                        wire:model="due_date"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('due_date') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    >
                                    @error('due_date')
                                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="edit-task-status" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Status <span class="text-rose-500">*</span>
                                    </label>
                                    <select 
                                        id="edit-task-status"
                                        wire:model="status"
                                        class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-900 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('status') border-rose-300 ring-2 ring-rose-100 @enderror"
                                    >
                                        <option value="pending">Pending</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description Field -->
                            <div>
                                <label for="edit-task-description" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Description / Notes
                                </label>
                                <textarea 
                                    id="edit-task-description"
                                    rows="3" 
                                    wire:model="description"
                                    class="w-full rounded-lg border border-slate-200 px-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors @error('description') border-rose-300 ring-2 ring-rose-100 @enderror"
                                ></textarea>
                                @error('description')
                                    <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Modal Actions Footer -->
                        <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-end gap-3">
                            <button 
                                type="button" 
                                wire:click="closeEditModal"
                                class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow-xs transition-colors cursor-pointer"
                            >
                                <span wire:loading.remove wire:target="updateTask">Save Changes</span>
                                <span wire:loading wire:target="updateTask">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- MODAL: TASK DETAIL                         -->
    <!-- ========================================== -->
    @if ($showDetailModal && $selectedTask)
        <div 
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-detail-title" 
            role="dialog" 
            aria-modal="true"
        >
            <!-- Backdrop -->
            <div 
                wire:click="closeDetailModal"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200/80"
                    @click.outside="$wire.closeDetailModal()"
                >
                    <!-- Header -->
                    <div class="flex items-start justify-between border-b border-slate-100 px-6 py-4">
                        <div class="space-y-1">
                            <div class="flex items-center gap-2">
                                @if ($selectedTask->category)
                                    <span 
                                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-medium"
                                        style="background-color: {{ $selectedTask->category->color }}15; color: {{ $selectedTask->category->color }}; border: 1px solid {{ $selectedTask->category->color }}30;"
                                    >
                                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $selectedTask->category->color }};"></span>
                                        {{ $selectedTask->category->name }}
                                    </span>
                                @endif

                                @if ($selectedTask->priority === 'high')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        High Priority
                                    </span>
                                @elseif ($selectedTask->priority === 'medium')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-700 border border-amber-200">
                                        Medium Priority
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        Low Priority
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 {{ $selectedTask->status === 'completed' ? 'line-through text-slate-500' : '' }}" id="modal-detail-title">
                                {{ $selectedTask->title }}
                            </h3>
                        </div>
                        <button 
                            type="button" 
                            wire:click="closeDetailModal"
                            class="rounded-lg p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="px-6 py-5 space-y-5">
                        <!-- Status Bar in Detail -->
                        <div class="rounded-xl bg-slate-50 border border-slate-200/70 p-3.5 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block">Current Status</span>
                                <div class="mt-1 flex items-center gap-2">
                                    @if ($selectedTask->status === 'completed')
                                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700">
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Completed
                                        </span>
                                    @elseif ($selectedTask->status === 'in_progress')
                                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-700">
                                            <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                            In Progress
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-700">
                                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                            Pending
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Quick change status buttons -->
                            <div class="flex items-center gap-1">
                                @if ($selectedTask->status !== 'pending')
                                    <button 
                                        type="button" 
                                        wire:click="updateStatus({{ $selectedTask->id }}, 'pending')"
                                        class="px-2.5 py-1 text-xs font-medium rounded-md border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 transition-colors"
                                    >
                                        Set Pending
                                    </button>
                                @endif
                                @if ($selectedTask->status !== 'in_progress')
                                    <button 
                                        type="button" 
                                        wire:click="updateStatus({{ $selectedTask->id }}, 'in_progress')"
                                        class="px-2.5 py-1 text-xs font-medium rounded-md border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors"
                                    >
                                        Set In Progress
                                    </button>
                                @endif
                                @if ($selectedTask->status !== 'completed')
                                    <button 
                                        type="button" 
                                        wire:click="updateStatus({{ $selectedTask->id }}, 'completed')"
                                        class="px-2.5 py-1 text-xs font-medium rounded-md border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors"
                                    >
                                        Complete
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div>
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-2">Description & Notes</span>
                            @if ($selectedTask->description)
                                <div class="rounded-xl border border-slate-200/80 bg-slate-50/50 p-4 text-sm text-slate-800 leading-relaxed whitespace-pre-line">
                                    {{ $selectedTask->description }}
                                </div>
                            @else
                                <p class="text-xs text-slate-400 italic">No detailed notes added for this task.</p>
                            @endif
                        </div>

                        <!-- Metadata Grid -->
                        <div class="grid grid-cols-2 gap-4 pt-2 border-t border-slate-100 text-xs">
                            <div>
                                <span class="text-slate-400 font-medium block">Deadline (Due Date)</span>
                                <div class="mt-1 font-semibold {{ $selectedTask->isOverdue() ? 'text-rose-600' : 'text-slate-800' }}">
                                    @if ($selectedTask->due_date)
                                        {{ $selectedTask->due_date->format('l, F j, Y') }}
                                        @if ($selectedTask->isOverdue())
                                            <span class="block text-[11px] font-medium text-rose-500">
                                                (Overdue by {{ abs(now()->startOfDay()->diffInDays($selectedTask->due_date)) }} days)
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-slate-400 font-normal">None</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <span class="text-slate-400 font-medium block">Completed Date</span>
                                <span class="mt-1 block font-semibold text-slate-800">
                                    {{ $selectedTask->completed_at ? $selectedTask->completed_at->format('M d, Y H:i') : 'Not completed yet' }}
                                </span>
                            </div>

                            <div>
                                <span class="text-slate-400 font-medium block">Created On</span>
                                <span class="mt-1 block text-slate-700">
                                    {{ $selectedTask->created_at->format('M d, Y H:i') }}
                                </span>
                            </div>

                            <div>
                                <span class="text-slate-400 font-medium block">Last Updated</span>
                                <span class="mt-1 block text-slate-700">
                                    {{ $selectedTask->updated_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="border-t border-slate-100 bg-slate-50/50 px-6 py-4 flex items-center justify-between">
                        <button 
                            type="button" 
                            wire:click="confirmDelete({{ $selectedTask->id }})"
                            class="text-xs font-medium text-rose-600 hover:text-rose-700 hover:underline cursor-pointer"
                        >
                            Delete Task
                        </button>

                        <div class="flex items-center gap-2.5">
                            <button 
                                type="button" 
                                wire:click="closeDetailModal"
                                class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                            >
                                Close
                            </button>
                            <button 
                                type="button" 
                                wire:click="closeDetailModal; openEditModal({{ $selectedTask->id }})"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 shadow-xs transition-colors cursor-pointer"
                            >
                                Edit Task
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================== -->
    <!-- MODAL: DELETE CONFIRMATION                 -->
    <!-- ========================================== -->
    @if ($showDeleteModal)
        <div 
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-delete-title" 
            role="dialog" 
            aria-modal="true"
        >
            <!-- Backdrop -->
            <div 
                wire:click="closeDeleteModal"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs transition-opacity"
            ></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div 
                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md border border-slate-200/80 p-6"
                    @click.outside="$wire.closeDeleteModal()"
                >
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-base font-bold text-slate-900" id="modal-delete-title">Delete Task</h3>
                            <p class="mt-1.5 text-sm text-slate-500">
                                Are you sure you want to delete <span class="font-semibold text-slate-800">"{{ $taskTitleBeingDeleted }}"</span>? This action cannot be undone.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            wire:click="closeDeleteModal"
                            class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button" 
                            wire:click="deleteTask"
                            class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700 shadow-xs transition-colors cursor-pointer"
                        >
                            <span wire:loading.remove wire:target="deleteTask">Delete Permanently</span>
                            <span wire:loading wire:target="deleteTask">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
