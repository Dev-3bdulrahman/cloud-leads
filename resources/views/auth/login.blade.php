<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SalesFlow — تسجيل الدخول</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .bg-pattern {
            background-color: #f8faff;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(99,102,241,0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(139,92,246,0.08) 0%, transparent 50%);
        }
    </style>
</head>
<body class="h-full bg-pattern flex items-center justify-center min-h-screen px-4">

    <div class="w-full max-w-md">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-tr from-indigo-600 to-violet-600 rounded-2xl shadow-xl shadow-indigo-200 mb-4">
                <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-600">SalesFlow</h1>
            <p class="text-slate-500 mt-1 text-sm">نظام إدارة المبيعات</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-2xl shadow-indigo-100/60 border border-slate-100 overflow-hidden">

            {{-- Card header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-6">
                <h2 class="text-xl font-bold text-white">مرحباً بك 👋</h2>
                <p class="text-indigo-200 text-sm mt-1">أدخل بياناتك للدخول إلى لوحة التحكم</p>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('login.post') }}" class="px-8 py-7 space-y-5">
                @csrf

                {{-- Error alert --}}
                @if($errors->any())
                <div class="flex items-center gap-3 p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-sm">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        البريد الإلكتروني
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input type="email" name="email" id="email"
                               value="{{ old('email') }}"
                               required autocomplete="email" autofocus
                               class="w-full pr-12 pl-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all
                                      @error('email') border-rose-300 bg-rose-50 @enderror"
                               placeholder="admin@example.com">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        كلمة المرور
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" name="password" id="password"
                               required autocomplete="current-password"
                               class="w-full pr-12 pl-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                               placeholder="••••••••">
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-400 cursor-pointer">
                    <label for="remember" class="text-sm text-slate-600 cursor-pointer select-none">
                        تذكّرني
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700
                               text-white rounded-2xl font-bold text-base shadow-xl shadow-indigo-100 active:scale-[0.98] transition-all
                               flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    تسجيل الدخول
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-400 mt-6">
            SalesFlow &copy; {{ date('Y') }} — جميع الحقوق محفوظة
        </p>
    </div>
</body>
</html>
