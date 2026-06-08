<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#bc000a" />
    <title>Masuk — Geprek Rejo</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-jakarta { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }
        .font-inter   { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .input-brand:focus {
            outline: none;
            border-color: #bc000a;
            box-shadow: 0 0 0 3px rgba(188, 0, 10, 0.12);
        }
    </style>
</head>

<body class="font-jakarta min-h-screen flex items-center justify-center bg-[#f7f9ff] antialiased px-6 py-10">

    {{-- Background decoration --}}
    <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
        <div class="absolute top-[-15%] right-[-10%] w-[45%] h-[45%] bg-[#bc000a]/5 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-15%] left-[-10%] w-[40%] h-[40%] bg-[#fdc003]/5 rounded-full blur-[100px]"></div>
    </div>

    <div class="w-full max-w-sm">

        {{-- Logo & brand --}}
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Ayam Geprek Rejo" class="h-32 w-auto object-contain mx-auto mb-4 drop-shadow-md">
            <h1 class="text-2xl font-extrabold text-[#181c20] hidden">Geprek Rejo</h1>
            <p class="font-inter text-sm text-[#5e3f3b] mt-1">Masuk ke sistem manajemen</p>
        </div>

        {{-- Session status --}}
        @if (session('status'))
            <div class="mb-5 flex items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                <span class="font-inter">{{ session('status') }}</span>
            </div>
        @endif

        {{-- Error alert --}}
        @if ($errors->any())
            <div class="mb-5 flex items-start gap-2 rounded-xl border border-[#ffdad6] bg-[#ffdad6]/50 px-4 py-3 text-sm text-[#93000a]">
                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div class="font-inter">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Login card --}}
        <div class="bg-white rounded-2xl shadow-[0_10px_30px_-12px_rgba(0,0,0,0.08)] border border-[#e0e3e8]/50 p-6">
            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="font-inter block text-[11px] font-bold text-[#5e3f3b] uppercase tracking-widest mb-2">Email</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="w-4 h-4 text-[#936e69]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input
                            id="email" name="email" type="email"
                            value="{{ old('email') }}"
                            required autofocus autocomplete="email"
                            placeholder="email@geprekrejo.com"
                            class="input-brand font-inter w-full rounded-xl border pl-10 pr-4 py-3 text-sm text-[#181c20] placeholder:text-[#936e69]/50 bg-white transition-all
                                   {{ $errors->has('email') ? 'border-[#ba1a1a]' : 'border-[#e0e3e8] hover:border-[#936e69]' }}"
                        >
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="font-inter block text-[11px] font-bold text-[#5e3f3b] uppercase tracking-widest">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="font-inter text-[11px] font-semibold text-[#bc000a] hover:text-[#930006] transition-colors">
                                Lupa password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                            <svg class="w-4 h-4 text-[#936e69]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            id="password" name="password" type="password"
                            required autocomplete="current-password"
                            placeholder="••••••••"
                            class="input-brand font-inter w-full rounded-xl border pl-10 pr-11 py-3 text-sm text-[#181c20] placeholder:text-[#936e69]/50 bg-white transition-all
                                   {{ $errors->has('password') ? 'border-[#ba1a1a]' : 'border-[#e0e3e8] hover:border-[#936e69]' }}"
                        >
                        <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#936e69] hover:text-[#bc000a] transition-colors"
                            onclick="const i=document.getElementById('password'); i.type = i.type==='password' ? 'text' : 'password'; this.querySelector('.eye-open').classList.toggle('hidden'); this.querySelector('.eye-closed').classList.toggle('hidden');">
                            <svg class="w-4 h-4 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg class="w-4 h-4 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <label class="flex items-center gap-2.5 cursor-pointer group" for="remember">
                    <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                           class="w-4 h-4 rounded border-[#e0e3e8] text-[#bc000a] focus:ring-[#bc000a]/30 transition">
                    <span class="font-inter text-sm text-[#5e3f3b]">Ingat saya</span>
                </label>

                {{-- Submit --}}
                <button type="submit" class="w-full py-3.5 bg-[#bc000a] text-white font-bold rounded-xl shadow-lg shadow-[#bc000a]/25 hover:bg-[#c0000b] active:scale-[0.98] transition-all">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="font-inter text-center text-xs text-[#936e69] mt-6">
            © {{ date('Y') }} Geprek Rejo
        </p>

    </div>

    @fluxScripts
</body>
</html>
