@extends('layouts.app')

@section('title', 'หน้าหลักเจ้าหน้าที่ | CarBooking')
@section('header', 'ระบบจองรถยนต์ส่วนกลาง')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/calendar-custom.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 transition-colors duration-300">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 transition-colors">สวัสดีคุณ {{ auth()->user()->name }}  👋</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 transition-colors">ยินดีต้อนรับสู่ระบบจองรถยนต์ คุณสามารถตรวจสอบสถานะและจองคิวรถได้ที่นี่</p>
        </div>
        <a href="{{ route('bookings.create') }}" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white px-6 py-3 rounded-xl font-bold transition-all shadow-sm flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            จองรถยนต์
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- การ์ด 1: รายการจองทั้งหมด --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center transition-colors duration-300">
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mr-4 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <h3 class="text-gray-500 dark:text-slate-400 text-sm font-medium transition-colors">รายการจองทั้งหมด</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white transition-colors">{{ $totalCount ?? 0 }} <span class="text-sm font-normal text-gray-500 dark:text-slate-400">ครั้ง</span></p>
            </div>
        </div>

        {{-- การ์ด 2: รอพิจารณา --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center transition-colors duration-300">
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-amber-900/30 text-yellow-600 dark:text-amber-400 mr-4 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-gray-500 dark:text-slate-400 text-sm font-medium transition-colors">รอพิจารณา</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white transition-colors">{{ $pendingCount ?? 0 }} <span class="text-sm font-normal text-gray-500 dark:text-slate-400">รายการ</span></p>
            </div>
        </div>

        {{-- การ์ด 3: อนุมัติแล้ว --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center transition-colors duration-300">
            <div class="p-3 rounded-full bg-green-100 dark:bg-emerald-900/30 text-green-600 dark:text-emerald-400 mr-4 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-gray-500 dark:text-slate-400 text-sm font-medium transition-colors">อนุมัติแล้ว</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white transition-colors">{{ $approvedCount ?? 0 }} <span class="text-sm font-normal text-gray-500 dark:text-slate-400">รายการ</span></p>
            </div>
        </div>

        {{-- การ์ด 4: ไม่อนุมัติ --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 flex items-center transition-colors duration-300">
            <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 mr-4 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-gray-500 dark:text-slate-400 text-sm font-medium transition-colors">ไม่อนุมัติ</h3>
                <p class="text-2xl font-bold text-gray-800 dark:text-white transition-colors">{{ $rejectedCount ?? 0 }} <span class="text-sm font-normal text-gray-500 dark:text-slate-400">รายการ</span></p>
            </div>
        </div>

    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-8 transition-colors duration-300">
        <div class="p-6 border-b border-gray-50 dark:border-slate-700 flex justify-between items-center bg-gray-50/30 dark:bg-slate-800/50 transition-colors">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-slate-100 transition-colors">📅 ปฏิทินการใช้รถยนต์ส่วนกลาง</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 transition-colors">คลิกที่แถบสีเพื่อดูรายละเอียดผู้จองและปลายทาง</p>
            </div>
            <div class="flex gap-4 text-xs font-medium text-slate-700 dark:text-slate-300 transition-colors">
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span> อนุมัติแล้ว</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span> รอพิจารณา</span>
            </div>
        </div>
        
        <div class="p-6">
            <div id="calendar" class="min-h-[600px] text-slate-800 dark:text-slate-200" data-events-url="{{ route('api.calendar.events') }}"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/calendar-init.js') }}?v={{ time() }}"></script>
@endpush