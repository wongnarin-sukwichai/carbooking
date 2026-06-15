@extends('layouts.app')

@section('title', 'หน้าหลักผู้ดูแลระบบ | CarBooking')
@section('header', 'ผู้ดูแลระบบ (Admin Dashboard)')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/calendar-custom.css') }}">
@endpush

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium">รถยนต์ในระบบ</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalCars }} <span class="text-sm font-normal text-gray-500">คัน</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium">ผู้ใช้งานทั้งหมด</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $totalUsers }} <span class="text-sm font-normal text-gray-500">คน</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium">รอการอนุมัติ</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $pendingBookings }} <span class="text-sm font-normal text-gray-500">รายการ</span></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium">กำลังใช้งานวันนี้</h3>
                <p class="text-2xl font-bold text-gray-800">{{ $activeToday }} <span class="text-sm font-normal text-gray-500">คัน</span></p>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">📅 ปฏิทินการใช้รถยนต์ส่วนกลาง</h2>
                <p class="text-sm text-gray-500 mt-1">คลิกที่แถบสีเพื่อดูรายละเอียดผู้จองและปลายทาง</p>
            </div>
            <div class="flex gap-4 text-xs font-medium">
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-emerald-500 mr-2"></span> อนุมัติแล้ว</span>
                <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-amber-500 mr-2"></span> รอพิจารณา</span>
            </div>
        </div>
        
        <div class="p-6">
            <div id="calendar" class="min-h-[600px]" data-events-url="{{ route('api.calendar.events') }}"></div>
        </div>
    </div>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('js/calendar-init.js') }}"></script>
@endpush
@endsection