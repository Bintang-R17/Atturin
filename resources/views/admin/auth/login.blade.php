<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ProSports Hub') }} — Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#E6EEFF',
                            100: '#CCDDFF',
                            200: '#99BBFF',
                            300: '#6699FF',
                            400: '#3377FF',
                            500: '#0052FF',
                            600: '#0042CC',
                            700: '#003199',
                            800: '#002166',
                            900: '#0A1628',
                        },
                        surface: '#EEF1F6',
                    },
                    fontFamily: {
                        sans: ['Lexend', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Lexend', sans-serif; }
    </style>
</head>
<body class="bg-surface min-h-screen flex items-center justify-center px-4">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-white via-[#F4F7FF] to-[#E7ECF9]"></div>
        <div class="absolute -top-24 -right-16 w-72 h-72 rounded-full bg-brand-100 blur-3xl opacity-70"></div>
        <div class="absolute bottom-10 left-10 w-60 h-60 rounded-full bg-blue-200 blur-3xl opacity-60"></div>
    </div>

    <div class="relative w-full max-w-4xl grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="hidden lg:flex flex-col justify-between p-8 rounded-3xl bg-white/70 border border-white shadow-xl">
            <div>
                <h1 class="text-2xl font-bold text-brand-700">PROSPORTS HUB</h1>
                <p class="text-xs font-semibold text-gray-400 tracking-wider uppercase mt-1">Admin Console</p>
            </div>
            <div>
                <p class="text-2xl font-semibold text-gray-900 leading-snug">Kelola event, keuangan, dan komunitas dalam satu dashboard.</p>
                <div class="mt-6 space-y-3">
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-500"></span>
                        Pantau peserta dan pembayaran real time.
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-400"></span>
                        Kirim notifikasi untuk update event.
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-brand-300"></span>
                        Atur konfigurasi admin dengan cepat.
                    </div>
                </div>
            </div>
            <div class="text-xs text-gray-400">
                Need access? Hubungi super admin.
            </div>
        </div>

        <div class="bg-white/90 backdrop-blur border border-white rounded-3xl shadow-xl p-8 lg:p-10">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Admin Login</h2>
                    <p class="text-sm text-gray-500 mt-1">Masuk untuk melanjutkan ke dashboard.</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-brand-500 text-white flex items-center justify-center font-bold">PS</div>
            </div>

            <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5">
                @csrf

                @if($errors->any())
                    <div class="flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-400 focus:ring-2 focus:ring-brand-200" placeholder="admin@prosports.com">
                </div>
                <div>
                    <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="mt-2 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 placeholder-gray-400 focus:border-brand-400 focus:ring-2 focus:ring-brand-200" placeholder="Masukkan password">
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-500 focus:ring-brand-300">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="text-brand-500 hover:text-brand-700 font-medium">Lupa password?</a>
                </div>
                <button type="submit" class="w-full py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm shadow-lg shadow-brand-500/20 transition-colors">
                    Masuk
                </button>
            </form>

            {{-- <div class="text-center text-sm text-gray-500">
                Atau masuk dengan
            </div>

            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-semibold text-gray-800 hover:bg-gray-50 transition-colors">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5">
                Google
            </a> --}}

            <div class="text-xs text-gray-500 text-center">
                Dengan login, Anda menyetujui kebijakan keamanan admin.
            </div>
        </div>
    </div>
</body>
</html>
