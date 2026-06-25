<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} &mdash; CheckApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="h-full bg-slate-50 antialiased">

    {{-- Navbar --}}
    <nav class="sticky top-0 z-30 border-b border-white/10 shadow-sm"
         style="background: linear-gradient(135deg, #0f2044 0%, #162d58 100%);">
        <div class="mx-auto max-w-5xl px-4 sm:px-6">
            <div class="flex h-16 items-center justify-between">

                {{-- Logo --}}
                <div class="flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: linear-gradient(135deg, #0d9488, #14b8a6); box-shadow: 0 2px 8px rgba(13,148,136,0.4);">
                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-white font-bold text-base">CheckApp</span>
                </div>

                {{-- Nav links --}}
                <div class="hidden sm:flex items-center gap-1">
                    <a href="{{ route('user.dashboard') }}" wire:navigate
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('user.dashboard') ? 'bg-white/15 text-white' : 'text-white/60 hover:bg-white/8 hover:text-white' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('user.history') }}" wire:navigate
                       class="px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150
                              {{ request()->routeIs('user.history') ? 'bg-white/15 text-white' : 'text-white/60 hover:bg-white/8 hover:text-white' }}">
                        Riwayat
                    </a>
                </div>

                {{-- Right --}}
                <div class="flex items-center gap-3" x-data="{ open: false }">
                    <div class="relative">
                        <button @click="open = !open"
                                class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl transition-all duration-150 hover:bg-white/10">
                            <div class="h-7 w-7 rounded-lg flex items-center justify-center text-xs font-bold text-white"
                                 style="background: linear-gradient(135deg, #0d9488, #0f766e);">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="hidden sm:block text-sm text-white/70 font-medium">{{ auth()->user()->name }}</span>
                            <svg class="h-3.5 w-3.5 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-44 bg-white rounded-2xl shadow-lg border border-gray-100 py-1.5 z-50">
                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="flex w-full items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Mobile menu --}}
                    <div class="sm:hidden" x-data="{ menuOpen: false }">
                        <button @click="menuOpen = !menuOpen" class="p-2 text-white/60 hover:text-white">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div x-show="menuOpen" x-cloak class="absolute top-16 left-0 right-0 border-t border-white/10 px-4 py-3 space-y-1"
                             style="background: #0f2044;">
                            <a href="{{ route('user.dashboard') }}" wire:navigate class="block px-3 py-2 rounded-xl text-sm text-white/70 hover:bg-white/10 hover:text-white">Dashboard</a>
                            <a href="{{ route('user.history') }}" wire:navigate class="block px-3 py-2 rounded-xl text-sm text-white/70 hover:bg-white/10 hover:text-white">Riwayat</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="mx-auto max-w-5xl px-4 sm:px-6 py-6 sm:py-8">
        @if (session('message'))
            <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-4 py-3 text-sm">
                <svg class="h-4 w-4 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ session('message') }}
            </div>
        @endif
        {{ $slot }}
    </main>

    <footer class="mt-8 pb-6 text-center">
        <p class="text-xs text-gray-400">CheckApp &copy; {{ date('Y') }}</p>
    </footer>

@livewireScripts
@stack('scripts')
</body>
</html>
