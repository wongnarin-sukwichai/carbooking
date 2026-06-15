@extends('layouts.app')

@section('title', 'รายการรอพิจารณาอนุมัติ | Head')
@section('header', 'คิวรถที่รอการพิจารณาอนุมัติ')

@section('content')
<div class="max-w-7xl mx-auto mt-2">
    
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-xl flex items-center justify-center font-bold shadow-sm transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 transition-colors">รายการรอพิจารณาอนุมัติด่วน</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400 transition-colors">มีคำขอทั้งหมด <span class="font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingBookings->count() }}</span> รายการ</p>
            </div>
        </div>
    </div>

    {{-- Grid แสดงการ์ดรายการ --}}
    @if($pendingBookings->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($pendingBookings as $pending)
            <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border-l-4 border-yellow-400 dark:border-yellow-500 hover:shadow-md transition-all flex flex-col h-full relative">
                
                {{-- ส่วนหัวการ์ด --}}
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <span class="inline-block px-2.5 py-1 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-[10px] font-bold rounded border border-yellow-200 dark:border-yellow-800/50 mb-2 shadow-sm transition-colors">
                            ⏳ รอพิจารณา
                        </span>
                        <h4 class="font-bold text-gray-800 dark:text-slate-100 text-lg leading-tight transition-colors">{{ $pending->user->name ?? 'ไม่ทราบชื่อ' }}</h4>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider transition-colors">รถที่จอง</p>
                        <p class="font-bold text-blue-600 dark:text-blue-400 text-sm mt-0.5 transition-colors">{{ $pending->car->car_name ?? '-' }}</p>
                    </div>
                </div>
                
                {{-- รายละเอียด --}}
                <div class="space-y-3 text-[13px] text-gray-600 dark:text-slate-300 mb-6 border-t border-gray-100 dark:border-slate-700 pt-4 flex-1 transition-colors">
                    <p class="flex items-start gap-2">
                        <span class="text-gray-400 dark:text-slate-500 mt-0.5 transition-colors">📍</span>
                        <span class="flex-1 line-clamp-2" title="{{ $pending->destination }}">
                            <strong class="text-gray-700 dark:text-slate-200 transition-colors">ปลายทาง:</strong> {{ $pending->destination }}
                        </span>
                    </p>
                    
                    <p class="flex items-start gap-2">
                        <span class="text-gray-400 dark:text-slate-500 mt-0.5 transition-colors">📝</span>
                        <span class="flex-1 line-clamp-2" title="{{ $pending->purpose }}">
                            <strong class="text-gray-700 dark:text-slate-200 transition-colors">เหตุผล:</strong> {{ $pending->purpose }}
                        </span>
                    </p>
                    
                    <div class="flex items-center gap-4 bg-gray-50/50 dark:bg-slate-700/50 p-2 rounded-lg border border-gray-100 dark:border-slate-600 transition-colors">
                        <p class="flex items-center gap-1.5 w-1/2">
                            <span class="text-gray-400 dark:text-slate-400">👥</span>
                            <span class="font-bold text-blue-600 dark:text-blue-400">{{ $pending->passenger_count }}</span> <span class="text-xs dark:text-slate-400">ท่าน</span>
                        </p>
                        <p class="flex items-center gap-1.5 w-1/2 border-l border-gray-200 dark:border-slate-600 pl-3 transition-colors">
                            <span class="text-gray-400 dark:text-slate-400">👨‍✈️</span>
                            <span class="font-bold text-indigo-600 dark:text-indigo-400 truncate" title="{{ $pending->driver ? $pending->driver->first_name . ' ' . $pending->driver->last_name : 'ไม่ระบุ' }}">
                                {{ $pending->driver ? $pending->driver->first_name . ' ' . $pending->driver->last_name : 'ไม่ระบุ' }}
                            </span>
                        </p>
                    </div>

                    <div class="bg-blue-50/50 dark:bg-blue-900/20 p-3 rounded-lg border border-blue-50 dark:border-blue-800/30 mt-3 transition-colors">
                        <p class="flex items-start gap-2 text-[12px]">
                            <span class="text-blue-400 dark:text-blue-500 mt-0.5 text-base transition-colors">🕒</span>
                            <span class="flex-1">
                                <span class="block mb-1"><strong class="text-gray-800 dark:text-slate-200 transition-colors">เริ่ม:</strong> {{ \Carbon\Carbon::parse($pending->start_time)->addYears(543)->format('d/m/Y H:i') }} น.</span>
                                <span class="block"><strong class="text-gray-800 dark:text-slate-200 transition-colors">ถึง:</strong> <span class="text-gray-500 dark:text-slate-400 transition-colors">{{ \Carbon\Carbon::parse($pending->end_time)->addYears(543)->format('d/m/Y H:i') }} น.</span></span>
                            </span>
                        </p>
                    </div>
                </div>

                {{-- ปุ่มพิจารณาด่วน --}}
                <button type="button" 
                        onclick="changeStatus('{{ route(Auth::user()->role === 'admin' ? 'admin.bookings.updateFastStatus' : 'head.bookings.updateFastStatus', $pending->id) }}', '{{ $pending->status }}', '')" 
                        class="w-full py-3 bg-white dark:bg-slate-800 hover:bg-blue-50 dark:hover:bg-slate-700 text-blue-600 dark:text-blue-400 border-2 border-blue-100 dark:border-slate-600 hover:border-blue-500 dark:hover:border-blue-500 rounded-xl text-sm font-bold transition-all flex justify-center items-center gap-2 text-center mt-auto shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    ตรวจสอบและพิจารณา
                </button>
            </div>
            @endforeach
        </div>
    @else
        {{-- กรณีไม่มีงานค้าง --}}
        <div class="bg-white dark:bg-slate-800 border border-green-100 dark:border-slate-700 p-10 rounded-3xl flex flex-col items-center justify-center text-center shadow-sm mt-4 transition-colors">
            <div class="w-20 h-20 bg-green-50 dark:bg-emerald-900/30 text-green-500 dark:text-emerald-400 rounded-full flex items-center justify-center mb-5 shadow-inner transition-colors">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 dark:text-slate-100 mb-2 transition-colors">ยอดเยี่ยมมาก! ไม่มีคิวค้าง</h3>
            <p class="text-gray-500 dark:text-slate-400 text-sm transition-colors">ขณะนี้ไม่มีรายการขอใช้รถยนต์ที่รอการพิจารณาในระบบ</p>
            
            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.bookings.index' : 'head.bookings.index') }}" 
               class="mt-8 px-8 py-3 bg-gray-50 dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 font-bold rounded-xl transition-colors text-sm border border-gray-200 dark:border-slate-600 shadow-sm">
                ดูประวัติการจองทั้งหมด
            </a>
        </div>
    @endif

    {{-- ตารางประวัติความเคลื่อนไหวล่าสุด --}}
    <div class="mt-12 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
        <div class="px-6 py-4 border-b border-gray-50 dark:border-slate-700 flex justify-between items-center bg-gray-50/50 dark:bg-slate-700/50 transition-colors">
            <h3 class="text-base font-bold text-gray-800 dark:text-slate-100 flex items-center transition-colors">
                <svg class="w-5 h-5 mr-2 text-gray-400 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                รายการจองล่าสุด (5 รายการ)
            </h3>
            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.bookings.index' : 'head.bookings.index') }}" class="text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                ดูประวัติทั้งหมด &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-white dark:bg-slate-800 text-gray-500 dark:text-slate-400 text-[11px] uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 transition-colors">
                        <th class="py-3 px-6 font-bold w-[25%]">ผู้จอง</th>
                        <th class="py-3 px-6 font-bold w-[35%]">รถยนต์ที่จอง</th>
                        <th class="py-3 px-6 font-bold w-[25%]">วัน-เวลาที่เดินทาง</th>
                        <th class="py-3 px-6 font-bold text-center w-[15%]">สถานะ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50 text-sm transition-colors">
                    @forelse($recentBookings as $recent)
                    <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="py-3.5 px-6">
                            <span class="font-bold text-gray-800 dark:text-slate-200 transition-colors">{{ $recent->user->name ?? '-' }}</span>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="text-gray-700 dark:text-slate-300 font-medium transition-colors">{{ $recent->car->car_name ?? '-' }}</span>
                        </td>
                        <td class="py-3.5 px-6 text-gray-500 dark:text-slate-400 text-xs transition-colors">
                            {{ \Carbon\Carbon::parse($recent->start_time)->addYears(543)->format('d/m/Y H:i') }} น.
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800/50',
                                    'approved' => 'bg-green-50 text-green-700 border-green-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50',
                                    'rejected' => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50',
                                ];
                                $statusLabels = [
                                    'pending' => 'รอพิจารณา',
                                    'approved' => 'อนุมัติแล้ว',
                                    'rejected' => 'ไม่อนุมัติ',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded text-[10px] font-bold border {{ $statusColors[$recent->status] ?? 'bg-gray-50 text-gray-800 dark:bg-slate-700 dark:text-slate-300' }} transition-colors">
                                {{ $statusLabels[$recent->status] ?? $recent->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400 dark:text-slate-500 text-sm transition-colors">ยังไม่มีรายการจองล่าสุดในระบบ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    {{-- โหลดเฉพาะสคริปต์ทำ Pop-up ด่วน --}}
    <script src="{{ asset('js/status-manager.js') }}?v={{ time() }}"></script>
@endpush
@endsection