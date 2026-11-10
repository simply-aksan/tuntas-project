@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Welcome Banner / Introduction -->
    <div class="rounded-xl border border-slate-200/80 bg-white p-6 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Welcome to TUNTAS</h2>
                <p class="mt-1 text-sm text-slate-500">
                    A streamlined, modern task management system engineered for students.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                    Phase 01: Project Foundation
                </span>
            </div>
        </div>
    </div>

    <!-- Foundation Verification Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Backend Card -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Backend Core</span>
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Laravel 13 & PHP 8.5</h3>
                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                    Framework initialized with Eloquent ORM, strict configuration, and secure routing foundation.
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400">
                Environment: local &bull; PHP {{ PHP_VERSION }}
            </div>
        </div>

        <!-- Reactivity Card -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Reactive Layer</span>
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Livewire 4</h3>
                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                    Zero-overhead SPA-like dynamic reactivity installed for student task operations without heavy frontend frameworks.
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400">
                Livewire v4.4 registered & ready
            </div>
        </div>

        <!-- Styling Card -->
        <div class="rounded-xl border border-slate-200/80 bg-white p-5 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">UI & Build</span>
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                </div>
                <h3 class="text-base font-semibold text-slate-900">Tailwind CSS 4 & Vite</h3>
                <p class="mt-1 text-xs text-slate-500 leading-relaxed">
                    Lightning fast official Vite integration with modern minimalist SaaS typography and clean tokens.
                </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400">
                Tailwind CSS v4 &bull; Vite v7
            </div>
        </div>
    </div>

    <!-- Development Status & Next Steps -->
    <div class="rounded-xl border border-slate-200/80 bg-white p-6 shadow-xs">
        <h3 class="text-sm font-semibold text-slate-900 mb-3">Foundation Milestone Checklist</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
            <div class="flex items-center gap-2.5 text-slate-700">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Laravel 13 application initialized</span>
            </div>
            <div class="flex items-center gap-2.5 text-slate-700">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Livewire 4 installed and registered</span>
            </div>
            <div class="flex items-center gap-2.5 text-slate-700">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Tailwind CSS 4 configured via Vite</span>
            </div>
            <div class="flex items-center gap-2.5 text-slate-700">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>MySQL 8 environment configurations mapped</span>
            </div>
            <div class="flex items-center gap-2.5 text-slate-700">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>Responsive SaaS layout shell implemented</span>
            </div>
            <div class="flex items-center gap-2.5 text-slate-500">
                <span class="w-4 h-4 rounded-full border border-slate-300 flex items-center justify-center text-[10px] text-slate-400 shrink-0">&bull;</span>
                <span>Ready for Phase 02 (Database ERD & Domain Models)</span>
            </div>
        </div>
    </div>
</div>
@endsection

