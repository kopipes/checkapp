<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} &mdash; CheckApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-link { transition: all 0.15s ease; }
        .sidebar-link:hover .nav-icon { transform: scale(1.1); }
    </style>
</head>
<body class="h-full bg-slate-50 antialiased" x-data="{ sidebarOpen: false }">

{{-- Mobile backdrop --}}
<div x-show="sidebarOpen"
     x-cloak
     x-transition:enter="transition-opacity ease-linear duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm lg:hidden"></div>

{{-- Sidebar --}}
<aside class="fixed inset-y-0 left-0 z-50 w-64 flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       style="background: linear-gradient(180deg, #0f2044 0%, #162d58 60%, #1a3a6e 100%);">

    {{-- Logo --}}
    <div class="flex h-16 items-center gap-3 px-5 border-b" style="border-color: rgba(255,255,255,0.08);">
        <div class="h-8 w-8 rounded-xl flex items-center justify-center flex-shrink-0"
             style="background: linear-gradient(135deg, #0d9488, #14b8a6); box-shadow: 0 2px 8px rgba(13,148,136,0.4);">
            <svg class="h-4.5 w-4.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:18px;height:18px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        <div>
            <p class="text-white font-bold text-base leading-tight">CheckApp</p>
            <p class="text-xs" style="color: rgba(255,255,255,0.4);">Admin Panel</p>
        </div>
    </div>

    {{-- User info --}}
    <div class="px-4 py-3.5 border-b" style="border-color: rgba(255,255,255,0.08);">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-xl flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                 style="background: linear-gradient(135deg, #0d9488, #0f766e);">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0">
                <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs" style="color: #5eead4;">Administrator</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto scrollbar-thin">
        @php
            $navGroups = [
                [
                    'label' => 'UTAMA',
                    'items' => [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z" />'],
                        ['route' => 'admin.health-checks.create', 'label' => 'Input Pemeriksaan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4v16m8-8H4" />'],
                    ],
                ],
                [
                    'label' => 'DATA',
                    'items' => [
                        ['route' => 'admin.health-checks.index', 'label' => 'Data Pemeriksaan', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />'],
                        ['route' => 'admin.users.index', 'label' => 'Manajemen User', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />'],
                        ['route' => 'admin.reports.index', 'label' => 'Report', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />'],
                    ],
                ],
                [
                    'label' => 'PENGATURAN',
                    'items' => [
                        ['route' => 'admin.thresholds.index', 'label' => 'Ambang Batas', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />'],
                    ],
                ],
            ];
        @endphp

        @foreach ($navGroups as $group)
            <div class="pt-3 pb-1">
                <p class="px-3 text-xs font-semibold mb-1" style="color: rgba(255,255,255,0.3); letter-spacing: 0.08em;">{{ $group['label'] }}</p>
                @foreach ($group['items'] as $item)
                    @php $active = request()->routeIs($item['route']); @endphp
                    <a href="{{ route($item['route']) }}"
                       wire:navigate
                       class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium mb-0.5 group
                              {{ $active ? 'bg-white/12 text-white' : 'text-white/60 hover:text-white hover:bg-white/8' }}"
                       style="{{ $active ? 'box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);' : '' }}">
                        <svg class="nav-icon h-5 w-5 flex-shrink-0 {{ $active ? 'text-teal-400' : 'text-white/40 group-hover:text-white/70' }}"
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $item['icon'] !!}</svg>
                        {{ $item['label'] }}
                        @if ($active)
                            <span class="ml-auto w-1 h-1 rounded-full bg-teal-400"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="p-3 border-t" style="border-color: rgba(255,255,255,0.08);">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex w-full items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-white/50 hover:bg-white/8 hover:text-white/80 transition-all duration-150">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Main content --}}
<div class="lg:pl-64 flex flex-col min-h-screen">

    {{-- Topbar --}}
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm">
        <div class="flex h-16 items-center gap-4 px-4 sm:px-6">
            {{-- Mobile menu --}}
            <button @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="flex-1">
                <h1 class="text-base font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-100 rounded-xl">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-xs text-gray-500 font-medium">{{ now()->locale('id')->translatedFormat('d M Y') }}</span>
                </div>
                <div class="h-8 w-8 rounded-xl flex items-center justify-center text-xs font-bold text-white"
                     style="background: linear-gradient(135deg, #0f2044, #162d58);">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </div>
    </header>

    {{-- Page --}}
    <main class="flex-1 p-4 sm:p-6 lg:p-8">
        @if (session('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3 text-sm shadow-sm">
                <div class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                    <svg class="h-3.5 w-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </div>
                <span>{{ session('message') }}</span>
                <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="px-6 py-4 border-t border-gray-100">
        <p class="text-xs text-gray-400 text-center">CheckApp &copy; {{ date('Y') }} &mdash; Sistem Pemeriksaan Kesehatan</p>
    </footer>
</div>

@livewireScripts
@stack('scripts')
</body>
</html>
