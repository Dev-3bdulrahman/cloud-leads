@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-8">
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">إدارة الموظفين</h1>
            <p class="text-slate-500 mt-1">إضافة وتعديل فريق المبيعات ومتابعة أدائهم</p>
        </div>
        <a href="{{ route('users.create') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            إضافة موظف جديد
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($users as $user)
            <div class="glass p-6 rounded-3xl hover:shadow-xl hover:shadow-slate-200/50 transition-all border border-white group">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-indigo-100">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">المهام الحالية</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ $user->leads_count }}</p>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">الدور</p>
                        <p class="text-sm font-bold text-slate-700">مبيعات</p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="flex-1" onsubmit="return confirm('هل أنت متأكد من حذف هذا الموظف؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full py-2.5 bg-rose-50 text-rose-600 rounded-xl font-medium hover:bg-rose-100 transition-all flex items-center justify-center gap-2 border border-rose-100">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                             حذف الموظف
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-20 glass rounded-3xl border-dashed border-2 border-slate-300">
                <p class="text-slate-500">لا يوجد موظفين حالياً في النظام</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
