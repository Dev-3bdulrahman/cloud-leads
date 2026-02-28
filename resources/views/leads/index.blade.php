@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-6" x-data="{ showModal: false }">

    {{-- ===== PAGE HEADER ===== --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-700 p-8 text-white shadow-2xl shadow-indigo-200">
        {{-- decorative circles --}}
        <div class="absolute -top-10 -left-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-white/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">📬 إدارة المبيعات</h1>
                <p class="text-indigo-200 mt-1 text-sm">تتبع وتوزيع رسائل البريد الإلكتروني الواردة على فريق المبيعات</p>
            </div>
            <button @click="showModal = true"
                class="shrink-0 flex items-center gap-2 px-6 py-3 bg-white text-indigo-700 rounded-2xl font-bold shadow-xl hover:bg-indigo-50 active:scale-95 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                إضافة بريد جديد
            </button>
        </div>
        {{-- Stats --}}
        <div class="relative mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3">
            @php
                $totalAll   = \App\Models\Lead::count();
                $newCount   = $leads->where('status','new')->count();
                $assigned   = $leads->where('status','assigned')->count();
                $closed     = $leads->where('status','closed')->count();
            @endphp
            <div class="bg-white/15 backdrop-blur rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold">{{ $leads->count() }}</p>
                <p class="text-indigo-200 text-xs mt-0.5">رسائل الفترة</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-blue-200">{{ $newCount }}</p>
                <p class="text-indigo-200 text-xs mt-0.5">جديدة</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-amber-200">{{ $assigned }}</p>
                <p class="text-indigo-200 text-xs mt-0.5">مُعيَّنة</p>
            </div>
            <div class="bg-white/15 backdrop-blur rounded-2xl p-4 text-center">
                <p class="text-2xl font-extrabold text-emerald-200">{{ $closed }}</p>
                <p class="text-indigo-200 text-xs mt-0.5">مكتملة</p>
            </div>
        </div>
    </div>

    {{-- ===== DATE FILTER ===== --}}
    <form method="GET" action="{{ route('leads.index') }}"
          class="flex flex-col sm:flex-row items-end gap-3 bg-white border border-slate-100 rounded-2xl px-6 py-4 shadow-sm">
        <div class="flex-1">
            <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">من تاريخ</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}"
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm
                          focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-bold text-slate-500 mb-1.5 uppercase tracking-wide">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ $dateTo }}"
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm
                          focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all">
        </div>
        <button type="submit"
                class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-sm shadow-md shadow-indigo-100 active:scale-95 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/>
            </svg>
            تصفية
        </button>
        @if($dateFrom !== \Carbon\Carbon::today()->toDateString() || $dateTo !== \Carbon\Carbon::today()->toDateString())
        <a href="{{ route('leads.index') }}"
           class="flex items-center gap-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-medium text-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            إعادة تعيين
        </a>
        @endif
    </form>

    {{-- ===== LEADS LIST ===== --}}
    <div class="flex flex-col gap-4">
        @forelse($leads as $lead)
        @php
            $statusStyles = [
                'new'        => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',   'border' => 'border-blue-200',   'dot' => 'bg-blue-500',    'label' => 'جديد'],
                'assigned'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',  'border' => 'border-amber-200',  'dot' => 'bg-amber-500',   'label' => 'تم التعيين'],
                'contacting' => ['bg' => 'bg-indigo-50',  'text' => 'text-indigo-700', 'border' => 'border-indigo-200', 'dot' => 'bg-indigo-500',  'label' => 'جاري التواصل'],
                'closed'     => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700','border' => 'border-emerald-200','dot' => 'bg-emerald-500', 'label' => 'مكتمل'],
            ];
            $s = $statusStyles[$lead->status] ?? $statusStyles['new'];
        @endphp
        <div class="bg-white border border-slate-100 rounded-2xl p-5 shadow-sm hover:shadow-lg hover:shadow-indigo-50/60 transition-all duration-300 group">
            <div class="flex flex-col lg:flex-row justify-between items-start gap-5">

                {{-- LEFT: Lead Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        {{-- Status Badge --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border {{ $s['bg'] }} {{ $s['text'] }} {{ $s['border'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $s['dot'] }}"></span>
                            {{ $s['label'] }}
                        </span>
                        <span class="text-xs text-slate-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $lead->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 group-hover:text-indigo-600 transition-colors truncate">
                        {{ $lead->subject ?? '— بدون عنوان —' }}
                    </h3>
                    <p class="text-slate-500 text-sm mt-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="font-medium text-slate-700">{{ $lead->sender_name ?? 'مرسل غير معروف' }}</span>
                        <span class="text-slate-300">|</span>
                        <span class="text-indigo-500 font-mono text-xs">{{ $lead->sender_email }}</span>
                    </p>
                    <div class="mt-3 p-3.5 bg-slate-50 rounded-xl text-slate-600 text-sm leading-relaxed border border-slate-100 line-clamp-3">
                        {{ $lead->body }}
                    </div>
                </div>

                {{-- RIGHT: Assignment Panel --}}
                <div class="w-full lg:w-64 shrink-0 flex flex-col gap-3">
                    {{-- Current assignee chip --}}
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">المسؤول الحالي</p>
                        @if($lead->assignedTo)
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white text-sm font-bold shadow-md shadow-indigo-100 shrink-0">
                                {{ mb_substr($lead->assignedTo->name, 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-800 text-sm truncate">{{ $lead->assignedTo->name }}</p>
                                <p class="text-xs text-slate-400">مندوب مبيعات</p>
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-slate-400 italic flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            غير مُعيَّن بعد
                        </p>
                        @endif
                    </div>

                    {{-- Assign form --}}
                    <form action="{{ route('leads.assign', $lead) }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <div class="relative">
                            <select name="user_id"
                                    class="w-full appearance-none px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-700
                                           focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all
                                           hover:border-indigo-300 shadow-sm pr-10">
                                <option value="">اختر موظفاً...</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $lead->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700
                                       text-white rounded-xl font-semibold text-sm shadow-md shadow-indigo-100 active:scale-95 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            إسناد المهمة
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white border-2 border-dashed border-slate-200 rounded-3xl">
            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-700">لا توجد رسائل في هذه الفترة</h3>
            <p class="text-slate-400 mt-2 text-sm">جرّب تغيير نطاق التاريخ أو أضف بريداً جديداً الآن</p>
            <button @click="showModal = true"
                    class="inline-flex items-center gap-2 mt-6 px-8 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                إضافة بريد الآن
            </button>
        </div>
        @endforelse
    </div>

{{-- ===== MODAL: Add New Lead ===== --}}
<div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        {{-- Backdrop --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
             @click="showModal = false">
        </div>

        {{-- Dialog --}}
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden">

            {{-- Modal header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-6 flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-extrabold text-white">إضافة بريد وارد جديد</h3>
                    <p class="text-indigo-200 text-sm mt-0.5">أدخل تفاصيل الرسالة وأسندها لموظف إن أردت</p>
                </div>
                <button @click="showModal = false"
                        class="w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal body --}}
            <form action="{{ route('leads.store') }}" method="POST" class="p-8 space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">اسم المرسل</label>
                        <input type="text" name="sender_name"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                               placeholder="مثلاً: محمد علي">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">
                            بريد المرسل <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="sender_email" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                               placeholder="customer@example.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">عنوان الرسالة (Subject)</label>
                    <input type="text" name="subject"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all"
                           placeholder="استفسار عن خدمات المبيعات">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        محتوى البريد <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="body" required rows="4"
                              class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 text-sm
                                     focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all resize-none"
                              placeholder="اكتب تفاصيل الرسالة هنا..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">إسناد لموظف (اختياري)</label>
                    <div class="relative">
                        <select name="assigned_to"
                                class="w-full appearance-none px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition-all pr-10">
                            <option value="">اختر موظف لإسناد المهمة فوراً...</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700
                               text-white rounded-2xl font-bold text-base shadow-xl shadow-indigo-100 active:scale-[0.98] transition-all mt-2
                               flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ وإسناد الرسالة
                </button>
            </form>
        </div>
    </div>
</div>

</div>{{-- end x-data --}}
@endsection
