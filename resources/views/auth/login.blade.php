<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk &mdash; CheckApp</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50">

<div class="min-h-screen flex">

    {{-- Left: Branding --}}
    <div class="hidden lg:flex lg:w-[52%] relative overflow-hidden flex-col justify-between p-12"
         style="background: linear-gradient(135deg, #0f2044 0%, #1a3a6e 50%, #0d9488 100%);">

        {{-- Decorative circles --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full opacity-10"
             style="background: radial-gradient(circle, #ffffff 0%, transparent 70%);"></div>
        <div class="absolute -bottom-32 -left-16 w-80 h-80 rounded-full opacity-10"
             style="background: radial-gradient(circle, #0d9488 0%, transparent 70%);"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full opacity-5"
             style="background: radial-gradient(circle, #ffffff 0%, transparent 70%);"></div>

        {{-- Logo --}}
        <div class="relative flex items-center gap-3">
            <div class="h-11 w-11 rounded-2xl flex items-center justify-center shadow-lg"
                 style="background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <span class="text-white text-xl font-bold tracking-tight">CheckApp</span>
        </div>

        {{-- Main copy --}}
        <div class="relative">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-medium mb-6"
                 style="background: rgba(255,255,255,0.12); color: #5eead4; border: 1px solid rgba(94,234,212,0.3);">
                <span class="w-1.5 h-1.5 rounded-full bg-teal-400 animate-pulse"></span>
                Sistem Cek Kesehatan Karyawan
            </div>
            <h1 class="text-white text-5xl font-bold leading-tight mb-5 text-balance">
                Pantau Kesehatan<br>
                <span style="color: #5eead4;">Lebih Mudah</span>
            </h1>
            <p class="text-white/60 text-lg leading-relaxed max-w-sm">
                Catat, pantau, dan analisis hasil pemeriksaan kesehatan karyawan secara cepat dan akurat.
            </p>
        </div>

        {{-- Stats --}}
        <div class="relative grid grid-cols-3 gap-4">
            @php
                $stats = [
                    ['value' => '5', 'label' => 'Parameter', 'sub' => 'Kesehatan'],
                    ['value' => 'Auto', 'label' => 'Status', 'sub' => 'Detection'],
                    ['value' => 'CSV', 'label' => 'Export', 'sub' => 'Report'],
                ];
            @endphp
            @foreach ($stats as $s)
                <div class="rounded-2xl p-4" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);">
                    <p class="text-white text-2xl font-bold">{{ $s['value'] }}</p>
                    <p style="color: #94d5d0;" class="text-xs font-medium mt-0.5">{{ $s['label'] }}</p>
                    <p class="text-white/40 text-xs">{{ $s['sub'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right: Login form --}}
    <div class="flex flex-1 flex-col justify-center px-6 sm:px-10 lg:px-16 bg-white">
        <div class="w-full max-w-sm mx-auto">

            {{-- Mobile logo --}}
            <div class="flex items-center gap-2.5 mb-10 lg:hidden">
                <div class="h-9 w-9 rounded-xl flex items-center justify-center"
                     style="background-color: #0f2044;">
                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <span class="font-bold text-lg" style="color: #0f2044;">CheckApp</span>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Selamat datang</h2>
                <p class="text-gray-500 text-sm mt-1">Masuk untuk melanjutkan ke dashboard.</p>
            </div>

            @if (session('status'))
                <div class="mb-5 flex items-center gap-2.5 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                    <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="label">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                           required autofocus autocomplete="username"
                           class="input @error('email') input-error @enderror"
                           placeholder="nama@checkapp.local" />
                    @error('email')
                        <p class="error-msg">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="label mb-0">Password</label>
                    </div>
                    <input id="password" name="password" type="password"
                           required autocomplete="current-password"
                           class="input @error('password') input-error @enderror"
                           placeholder="••••••••" />
                    @error('password')
                        <p class="error-msg">
                            <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember"
                               class="rounded border-gray-300 text-teal-600 focus:ring-teal-500 focus:ring-offset-0" />
                        <span class="text-sm text-gray-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full py-2.5 text-base">
                    Masuk
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </button>
            </form>

            <p class="text-center text-xs text-gray-400 mt-8">
                &copy; {{ date('Y') }} CheckApp &mdash; Sistem Pemeriksaan Kesehatan
            </p>
        </div>
    </div>
</div>

</body>
</html>
