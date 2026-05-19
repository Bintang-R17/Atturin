<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ProSports Hub') }}</title>
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
        .auth-input {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #E5E7EB;
            background: #fff;
            padding: 12px 16px;
            font-size: 14px;
            color: #1F2937;
            transition: all 0.2s ease;
        }
        .auth-input::placeholder { color: #9CA3AF; }
        .auth-input:focus {
            outline: none;
            border-color: #3377FF;
            box-shadow: 0 0 0 3px rgba(0, 82, 255, 0.1);
        }
    </style>
</head>
<body class="bg-surface min-h-screen flex items-center justify-center px-4 py-8">
    {{-- Decorative Background --}}
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-br from-white via-[#F4F7FF] to-[#E7ECF9]"></div>
        <div class="absolute -top-24 -right-16 w-72 h-72 rounded-full bg-brand-100 blur-3xl opacity-70"></div>
        <div class="absolute bottom-10 left-10 w-60 h-60 rounded-full bg-blue-200 blur-3xl opacity-60"></div>
        <div class="absolute top-1/2 left-1/3 w-40 h-40 rounded-full bg-brand-50 blur-2xl opacity-50"></div>
    </div>

    <div class="relative w-full max-w-md">
        {{-- Logo / Branding --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-block">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-brand-500 text-white flex items-center justify-center font-bold text-lg shadow-lg shadow-brand-500/25">
                    PS
                </div>
                <h1 class="text-lg font-bold text-brand-700 mt-3 tracking-tight">PROSPORTS HUB</h1>
                <p class="text-[11px] font-medium text-gray-400 tracking-wider uppercase mt-0.5">Admin Console</p>
            </a>
        </div>

        {{-- Card Container --}}
        <div class="bg-white/90 backdrop-blur border border-white rounded-3xl shadow-xl p-8">
            {{ $slot }}
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-6">
            &copy; {{ date('Y') }} ProSports Hub. All rights reserved.
        </p>
    </div>
</body>
</html>
