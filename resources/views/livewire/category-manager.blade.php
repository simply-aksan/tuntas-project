<div>
    @section('header', 'Category Management')

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

        <!-- Summary & Stats Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Total Categories -->
            <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Total Categories</span>
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                </div>
                <div class="mt-2 text-2xl font-bold text-slate-900">{{ $totalCategories }}</div>
                <p class="mt-0.5 text-xs text-slate-500">Academic & personal subjects</p>
            </div>

            <!-- Total Categorized Tasks -->
            <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Categorized Tasks</span>
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                </div>
                <div class="mt-2 text-2xl font-bold text-emerald-700">{{ $totalCategorizedTasks }}</div>
                <p class="mt-0.5 text-xs text-slate-500">Tasks assigned to a category</p>
            </div>

            <!-- Active Search Filter -->
            <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs">
                <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-wider text-slate-400">
                    <span>Active View</span>
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                </div>
                <div class="mt-2 text-2xl font-bold text-slate-800">{{ $categories->total() }}</div>
                <p class="mt-0.5 text-xs text-slate-500">
                    @if ($search)
                        Filtered by "{{ $search }}"
                    @else
                        All categories listed
                    @endif
                </p>
            </div>
        </div>

        <!-- Action Bar: Search & New Category Button -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-4 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Search Input -->
                <div class="relative flex-1 max-w-md">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search categories by name..." 
                        class="w-full pl-10 pr-4 py-2 text-sm border border-slate-200 rounded-lg bg-slate-50/50 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-colors"
                    >
                    @if ($search)
                        <button 
                            type="button" 
                            wire:click="resetSearch"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3">
                    <button 
                        type="button" 
                        wire:click="openCreateModal"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-xs transition-colors cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>New Category</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Grid Container -->
        @if ($categories->isEmpty())
            <div class="rounded-xl border border-slate-200/80 bg-white p-12 text-center shadow-xs">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-indigo-50 text-indigo-600 mb-4">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-slate-900">No categories found</h3>
                <p class="mt-1 text-sm text-slate-500 max-w-sm mx-auto">
                    @if ($search)
                        No categories match "{{ $search }}". Try clearing your search query.
                    @else
                        Organize your academic coursework, projects, and personal tasks by creating your first category.
                    @endif
                </p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    @if ($search)
                        <button 
                            type="button" 
                            wire:click="resetSearch" 
                            class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            Clear Search
                        </button>
                    @endif
                    <button 
                        type="button" 
                        wire:click="openCreateModal"
                        class="px-4 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-colors shadow-xs"
                    >
                        Create Category
                    </button>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                @foreach ($categories as $category)
                    <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs hover:border-slate-300 transition-all duration-150 flex flex-col justify-between">
                        <!-- Top: Color tag & Category Name -->
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-3">
                                <span 
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold tracking-wide border"
                                    style="background-color: {{ $category->color }}15; color: {{ $category->color }}; border-color: {{ $category->color }}30;"
                                >
                                    <span class="h-2 w-2 rounded-full" style="background-color: {{ $category->color }};"></span>
                                    <span>{{ $category->color }}</span>
                                </span>

                                <!-- Actions Dropdown / Buttons -->
                                <div class="flex items-center gap-1 text-slate-400">
                                    <button 
                                        type="button" 
                                        wire:click="openEditModal({{ $category->id }})"
                                        title="Edit category"
                                        class="p-1.5 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="confirmDelete({{ $category->id }})"
                                        title="Delete category"
                                        class="p-1.5 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 tracking-tight">{{ $category->name }}</h3>
                            <p class="mt-1 text-xs text-slate-500">
                                Created {{ $category->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <!-- Bottom: Task Count & Task Discovery Link -->
                        <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-600">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                <span>{{ $category->tasks_count }} {{ Str::plural('task', $category->tasks_count) }}</span>
                            </span>

                            <a 
                                href="{{ route('tasks.index', ['category' => $category->id]) }}"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 hover:text-indigo-700 transition-colors"
                            >
                                <span>Discover Tasks</span>
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Links -->
            @if ($categories->hasPages())
                <div class="rounded-xl border border-slate-200/80 bg-white px-5 py-3 shadow-xs">
                    {{ $categories->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Create Category Modal -->
    @if ($showCreateModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden transform transition-all"
                @click.outside="$wire.closeCreateModal()"
            >
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Create New Category</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Define a subject or task group with custom color coding.</p>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeCreateModal"
                        class="p-1 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form wire:submit.prevent="createCategory" class="px-6 py-5 space-y-5">
                    <!-- Category Name -->
                    <div>
                        <label for="create_category_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Category Name <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="create_category_name"
                            wire:model="name"
                            placeholder="e.g. Mathematics, Biology 101, Final Thesis" 
                            class="w-full px-3.5 py-2 text-sm border rounded-lg bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-colors {{ $errors->has('name') ? 'border-rose-300 ring-rose-100' : 'border-slate-200' }}"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category Color Selection -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                            Category Color <span class="text-rose-500">*</span>
                        </label>

                        <!-- Live Preview Tag -->
                        <div class="mb-3 p-3 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500">Live Badge Preview:</span>
                            <span 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold tracking-wide border transition-all"
                                style="background-color: {{ $color ?: '#6366f1' }}15; color: {{ $color ?: '#6366f1' }}; border-color: {{ $color ?: '#6366f1' }}30;"
                            >
                                <span class="h-2 w-2 rounded-full" style="background-color: {{ $color ?: '#6366f1' }};"></span>
                                <span>{{ $name ?: 'Category Preview' }}</span>
                            </span>
                        </div>

                        <!-- Preset Palette Swatches -->
                        <div class="grid grid-cols-6 gap-2 mb-3">
                            @foreach ($presetColors as $hex => $label)
                                <button 
                                    type="button" 
                                    wire:click="selectColor('{{ $hex }}')"
                                    title="{{ $label }} ({{ $hex }})"
                                    class="h-9 w-full rounded-lg flex items-center justify-center transition-all transform hover:scale-105 cursor-pointer relative {{ strtolower($color) === strtolower($hex) ? 'ring-2 ring-offset-2 ring-slate-800 shadow-xs' : 'border border-slate-200/60 hover:opacity-90' }}"
                                    style="background-color: {{ $hex }};"
                                >
                                    @if (strtolower($color) === strtolower($hex))
                                        <svg class="w-4 h-4 text-white drop-shadow-xs" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        <!-- Custom Color Input -->
                        <div class="flex items-center gap-2">
                            <div class="relative flex items-center">
                                <input 
                                    type="color" 
                                    wire:model.live="color" 
                                    class="h-9 w-12 p-0.5 rounded-lg border border-slate-200 cursor-pointer bg-white"
                                    title="Choose custom color"
                                >
                            </div>
                            <input 
                                type="text" 
                                wire:model.live="color"
                                placeholder="#6366f1"
                                class="flex-1 px-3 py-2 text-sm font-mono border rounded-lg bg-white text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 uppercase transition-colors {{ $errors->has('color') ? 'border-rose-300' : 'border-slate-200' }}"
                            >
                        </div>
                        @error('color')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            wire:click="closeCreateModal"
                            class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-xs transition-colors cursor-pointer"
                        >
                            <span wire:loading.remove wire:target="createCategory">Save Category</span>
                            <span wire:loading wire:target="createCategory">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Edit Category Modal -->
    @if ($showEditModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                class="w-full max-w-lg bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden transform transition-all"
                @click.outside="$wire.closeEditModal()"
            >
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Edit Category</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Update category label or color branding.</p>
                    </div>
                    <button 
                        type="button" 
                        wire:click="closeEditModal"
                        class="p-1 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Form Body -->
                <form wire:submit.prevent="updateCategory" class="px-6 py-5 space-y-5">
                    <!-- Category Name -->
                    <div>
                        <label for="edit_category_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-1.5">
                            Category Name <span class="text-rose-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="edit_category_name"
                            wire:model="name"
                            class="w-full px-3.5 py-2 text-sm border rounded-lg bg-white text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-colors {{ $errors->has('name') ? 'border-rose-300 ring-rose-100' : 'border-slate-200' }}"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category Color Selection -->
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-700 mb-2">
                            Category Color <span class="text-rose-500">*</span>
                        </label>

                        <!-- Live Preview Tag -->
                        <div class="mb-3 p-3 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-between">
                            <span class="text-xs text-slate-500">Live Badge Preview:</span>
                            <span 
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-md text-xs font-semibold tracking-wide border transition-all"
                                style="background-color: {{ $color ?: '#6366f1' }}15; color: {{ $color ?: '#6366f1' }}; border-color: {{ $color ?: '#6366f1' }}30;"
                            >
                                <span class="h-2 w-2 rounded-full" style="background-color: {{ $color ?: '#6366f1' }};"></span>
                                <span>{{ $name ?: 'Category Preview' }}</span>
                            </span>
                        </div>

                        <!-- Preset Palette Swatches -->
                        <div class="grid grid-cols-6 gap-2 mb-3">
                            @foreach ($presetColors as $hex => $label)
                                <button 
                                    type="button" 
                                    wire:click="selectColor('{{ $hex }}')"
                                    title="{{ $label }} ({{ $hex }})"
                                    class="h-9 w-full rounded-lg flex items-center justify-center transition-all transform hover:scale-105 cursor-pointer relative {{ strtolower($color) === strtolower($hex) ? 'ring-2 ring-offset-2 ring-slate-800 shadow-xs' : 'border border-slate-200/60 hover:opacity-90' }}"
                                    style="background-color: {{ $hex }};"
                                >
                                    @if (strtolower($color) === strtolower($hex))
                                        <svg class="w-4 h-4 text-white drop-shadow-xs" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </button>
                            @endforeach
                        </div>

                        <!-- Custom Color Input -->
                        <div class="flex items-center gap-2">
                            <div class="relative flex items-center">
                                <input 
                                    type="color" 
                                    wire:model.live="color" 
                                    class="h-9 w-12 p-0.5 rounded-lg border border-slate-200 cursor-pointer bg-white"
                                    title="Choose custom color"
                                >
                            </div>
                            <input 
                                type="text" 
                                wire:model.live="color"
                                placeholder="#6366f1"
                                class="flex-1 px-3 py-2 text-sm font-mono border rounded-lg bg-white text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 uppercase transition-colors {{ $errors->has('color') ? 'border-rose-300' : 'border-slate-200' }}"
                            >
                        </div>
                        @error('color')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            wire:click="closeEditModal"
                            class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow-xs transition-colors cursor-pointer"
                        >
                            <span wire:loading.remove wire:target="updateCategory">Save Changes</span>
                            <span wire:loading wire:target="updateCategory">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div 
                class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden transform transition-all"
                @click.outside="$wire.closeDeleteModal()"
            >
                <div class="p-6">
                    <div class="flex items-center gap-3.5 mb-4">
                        <div class="h-10 w-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Delete Category</h3>
                            <p class="text-xs text-slate-500">This action cannot be undone.</p>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 leading-relaxed">
                        Are you sure you want to delete <span class="font-semibold text-slate-900">"{{ $categoryNameBeingDeleted }}"</span>?
                    </p>

                    @if ($categoryTaskCountBeingDeleted > 0)
                        <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <p class="text-xs text-amber-800 leading-relaxed">
                                Notice: <strong>{{ $categoryTaskCountBeingDeleted }}</strong> associated {{ Str::plural('task', $categoryTaskCountBeingDeleted) }} will not be deleted, but will become uncategorized.
                            </p>
                        </div>
                    @endif

                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button 
                            type="button" 
                            wire:click="closeDeleteModal"
                            class="px-4 py-2 text-sm font-medium rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="button" 
                            wire:click="deleteCategory"
                            class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold rounded-lg bg-rose-600 text-white hover:bg-rose-700 shadow-xs transition-colors cursor-pointer"
                        >
                            <span wire:loading.remove wire:target="deleteCategory">Delete Category</span>
                            <span wire:loading wire:target="deleteCategory">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

