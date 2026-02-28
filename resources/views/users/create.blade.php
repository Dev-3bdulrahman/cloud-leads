@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('users.index') }}" class="text-indigo-600 hover:text-indigo-700 flex items-center gap-1 font-medium transition-colors mb-4">
            <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            العودة لقائمة الموظفين
        </a>
        <h1 class="text-3xl font-bold text-slate-900">إضافة موظف مبيعات جديد</h1>
        <p class="text-slate-500 mt-1">قم بإنشاء حساب جديد لأحد أفراد فريق المبيعات</p>
    </div>

    <div class="glass p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-white">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">اسم الموظف</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none bg-slate-50/50" placeholder="مثلاً: خالد مبيعات">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none bg-slate-50/50" placeholder="sales@example.com">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">كلمة المرور</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none bg-slate-50/50" placeholder="••••••••">
                <p class="text-xs text-slate-400 mt-2">يجب أن لا تقل كلمة المرور عن 8 خانات</p>
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white rounded-2xl font-bold text-lg hover:shadow-2xl hover:shadow-indigo-200 transition-all active:scale-[0.98] mt-4 shadow-lg shadow-indigo-100">
                حفظ بيانات الموظف
            </button>
        </form>
    </div>
</div>
@endsection
