<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50 antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' . config('app.name', 'TUNTAS') : config('app.name', 'TUNTAS') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="h-full text-slate-800 font-sans selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false }">
    <div class="min-h-full flex flex-col lg:flex-row">
        <!-- Mobile Sidebar Backdrop -->
        <div
            x-show="sidebarOpen"
            x-cloak
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs transition-opacity lg:hidden"
        ></div>

        <!-- App Sidebar Navigation -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white border-r border-slate-200/80 transition-transform duration-200 ease-in-out lg:static lg:z-auto"
        >
            <!-- Brand / Logo Area -->
            <div class="flex h-16 items-center justify-between px-6 border-b border-slate-100">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-white font-bold text-base shadow-xs">
                        T
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold tracking-tight text-slate-900 text-base leading-none">TUNTAS</span>
                        <span class="text-[11px] font-medium text-slate-400 leading-tight mt-0.5">Student Task Manager</span>
                    </div>
                </a>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100 lg:hidden"
                    aria-label="Close sidebar"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
                <div>
                    <p class="px-2 text-[11px] font-semibold uppercase tracking-wider text-slate-400 mb-2">Main Menu</p>
                    <nav class="space-y-1">
                        <a
                            href="{{ url('/') }}"
                            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->is('/') ? 'bg-indigo-50/70 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}"
                        >
                            <svg class="w-5 h-5 {{ request()->is('/') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Dashboard</span>
                        </a>

                        <a
                            href="{{ route('tasks.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('tasks.*') ? 'bg-indigo-50/70 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}"
                        >
                            <svg class="w-5 h-5 {{ request()->routeIs('tasks.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span>Tasks</span>
                        </a>

                        <a
                            href="{{ route('categories.index') }}"
                            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('categories.*') ? 'bg-indigo-50/70 text-indigo-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}"
                        >
                            <svg class="w-5 h-5 {{ request()->routeIs('categories.*') ? 'text-indigo-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span>Categories</span>
                        </a>
                    </nav>
                </div>
            </div>

            <!-- User Status / Footer Section -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="h-9 w-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold uppercase shrink-0">
                            {{ substr(auth()->user()->name ?? 'U', 0, 2) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name ?? 'Student' }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? 'student@tuntas.test' }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button 
                            type="submit" 
                            title="Sign out"
                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex flex-1 flex-col min-w-0 overflow-hidden">
            <!-- Header Bar -->
            <header class="h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200/80 sticky top-0 z-30">
                <div class="flex items-center gap-3">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        class="p-2 -ml-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 lg:hidden"
                        aria-label="Open sidebar"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <h1 class="text-lg font-semibold text-slate-900">
                        @yield('header', 'Dashboard')
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Foundation Ready
                    </div>
                </div>
            </header>

            <!-- Notification / Flash Alerts -->
            @if(session('success'))
                <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 rounded-lg bg-emerald-50 border border-emerald-200 p-3.5 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 sm:mx-6 lg:mx-8 mt-4 rounded-lg bg-rose-50 border border-rose-200 p-3.5 text-sm text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Page Body -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @hasSection('content')
                    @yield('content')
                @else
                    {{ $slot ?? '' }}
                @endif
            </main>

            <!-- Clean App Footer -->
            <footer class="px-4 sm:px-6 lg:px-8 py-4 border-t border-slate-200/60 bg-white text-xs text-slate-400 flex flex-col sm:flex-row items-center justify-between gap-2">
                <span>&copy; {{ date('Y') }} TUNTAS — Student Task Management System</span>
                <span class="text-slate-400">Laravel 13 &bull; Livewire 4 &bull; Tailwind CSS 4</span>
            </footer>
        </div>
    </div>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>

