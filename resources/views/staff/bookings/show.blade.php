@extends('layouts.app')

@section('title', 'รายละเอียดการจอง | CarBooking')
@section('header', 'รายละเอียดคำขอใช้รถยนต์')

{{-- ✨ เรียกใช้ไฟล์ CSS สำหรับ Print Mode --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/booking-print.css') }}?v={{ time() }}">
@endpush

@section('content')
{{-- ✨ ตัวแปรช่วยแปลงเดือนเป็นภาษาไทย --}}
@php
    $thMonthShort = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    $thMonthFull = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    $thMonthShort[1] = 'ม.ค.';
@endphp

<div class="max-w-4xl mx-auto mt-4 pb-16 px-4">

    {{-- 🔙 ส่วนควบคุมปุ่มต่างๆ (ซ่อนเมื่อสั่งพิมพ์) --}}
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 hide-on-print">
        <a href="{{ route('bookings.my_history') }}" class="inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 dark:text-slate-400 dark:hover:text-blue-400 transition-colors group">
            <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            ย้อนกลับ
        </a>
        
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="bg-blue-600 border border-transparent text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-md hover:bg-blue-700 hover:shadow-lg transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                พิมพ์ใบรายละเอียดการจองรถ
            </button>
        </div>
    </div>

    {{-- 🌟 1. Visual Status Timeline (ซ่อนตอน Print) --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/60 p-6 mb-6 transition-colors duration-300 hide-on-print">
        <div class="relative flex items-start justify-between w-full max-w-2xl mx-auto mt-2">
            <div class="absolute left-0 top-[1.25rem] transform w-full h-[3px] bg-gray-100 dark:bg-slate-700 rounded-full"></div>
            
            @php
                $status = $booking->status;
                $progress = '0%';
                if($status === 'pending') $progress = '50%';
                if($status === 'approved' || $status === 'rejected') $progress = '100%';

                $step2Color = $status === 'pending' ? 'bg-amber-500 ring-amber-100 dark:ring-amber-900/40' : ($status !== 'pending' ? 'bg-blue-600 ring-blue-100 dark:ring-blue-900/40' : 'bg-gray-200 dark:bg-slate-700');
                $step3Color = 'bg-gray-200 dark:bg-slate-700 ring-gray-50 dark:ring-slate-800';
                if($status === 'approved') $step3Color = 'bg-emerald-500 ring-emerald-100 dark:ring-emerald-900/40';
                if($status === 'rejected') $step3Color = 'bg-rose-500 ring-rose-100 dark:ring-rose-900/40';
            @endphp

            <div class="absolute left-0 top-[1.25rem] h-[3px] bg-blue-600 dark:bg-blue-500 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}; box-shadow: 0 0 8px rgba(37,99,235,0.4)"></div>

            {{-- Step 1 --}}
            <div class="relative z-10 flex flex-col items-center w-28">
                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center ring-4 ring-blue-50 dark:ring-blue-900/30 shadow-sm mb-3 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-xs font-bold text-gray-800 dark:text-slate-200">ส่งคำขอแล้ว</span>
                <div class="text-center mt-1">
                    <span class="block text-[11px] text-gray-500 dark:text-slate-400">
                        {{ \Carbon\Carbon::parse($booking->created_at)->timezone('Asia/Bangkok')->format('d') }} {{ $thMonthShort[\Carbon\Carbon::parse($booking->created_at)->timezone('Asia/Bangkok')->format('n')] }} {{ \Carbon\Carbon::parse($booking->created_at)->timezone('Asia/Bangkok')->addYears(543)->format('y') }}
                    </span>
                    <span class="block text-[10px] text-gray-400 dark:text-slate-500">{{ \Carbon\Carbon::parse($booking->created_at)->timezone('Asia/Bangkok')->format('H:i') }} น.</span>
                </div>
            </div>

            {{-- Step 2 --}}
            <div class="relative z-10 flex flex-col items-center w-28">
                <div class="w-10 h-10 rounded-full {{ $step2Color }} text-white flex items-center justify-center ring-4 shadow-sm mb-3 transition-all duration-500">
                    @if($status !== 'pending')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    @else
                        <svg class="w-5 h-5 animate-spin-slow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>
                <span class="text-xs font-bold text-gray-800 dark:text-slate-200">รอพิจารณา</span>
            </div>

            {{-- Step 3 --}}
            <div class="relative z-10 flex flex-col items-center w-28">
                <div class="w-10 h-10 rounded-full {{ $step3Color }} text-white flex items-center justify-center ring-4 shadow-sm mb-3 transition-all duration-500">
                    @if($status === 'approved')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    @elseif($status === 'rejected')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    @else
                        <svg class="w-5 h-5 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>
                <span class="text-xs font-bold text-gray-800 dark:text-slate-200">
                    {{ $status === 'approved' ? 'อนุมัติแล้ว' : ($status === 'rejected' ? 'ไม่อนุมัติ' : 'รอทราบผล') }}
                </span>
                
                @if($status !== 'pending')
                <div class="text-center mt-1">
                    <span class="block text-[11px] text-gray-500 dark:text-slate-400">
                        {{ \Carbon\Carbon::parse($booking->updated_at)->timezone('Asia/Bangkok')->format('d') }} {{ $thMonthShort[\Carbon\Carbon::parse($booking->updated_at)->timezone('Asia/Bangkok')->format('n')] }} {{ \Carbon\Carbon::parse($booking->updated_at)->timezone('Asia/Bangkok')->addYears(543)->format('y') }}
                    </span>
                    <span class="block text-[10px] text-gray-400 dark:text-slate-500">{{ \Carbon\Carbon::parse($booking->updated_at)->timezone('Asia/Bangkok')->format('H:i') }} น.</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 🌟 2. รายละเอียดการจอง (Compact View) --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/60 overflow-hidden transition-colors duration-300 print-container">
        
        {{-- Header Card --}}
        <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/80 print-header">
            <div class="flex flex-col">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">รายละเอียดการจองรถ</h2>
                <span class="text-sm font-mono text-gray-500 dark:text-slate-400 mt-1">Ref: #REQ-{{ \Carbon\Carbon::parse($booking->created_at)->timezone('Asia/Bangkok')->addYears(543)->format('Y') }}-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="flex items-center gap-4">
                @if($status === 'approved')
                    <span class="inline-flex items-center bg-[#ecfdf5] text-[#047857] border border-[#a7f3d0] px-3 py-1 rounded-md text-[11px] font-bold uppercase tracking-widest shadow-sm">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        อนุมัติแล้ว
                    </span>
                @elseif($status === 'pending')
                    <span class="inline-flex items-center bg-[#fefce8] text-[#b45309] border border-[#fde047] px-3 py-1 rounded-md text-[11px] font-bold shadow-sm">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        รอพิจารณา
                    </span>
                @elseif($status === 'rejected')
                    <span class="inline-flex items-center bg-[#fff1f2] text-[#be123c] border border-[#fecdd3] px-3 py-1 rounded-md text-[11px] font-bold shadow-sm">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                        ไม่อนุมัติ
                    </span>
                @endif
                
                {{-- โลโก้มุมขวา --}}
                <img src="{{ asset('img/car_logo.jpg') }}" alt="MSU Library Logo" class="h-10 sm:h-12 w-auto object-contain mix-blend-multiply dark:mix-blend-normal">
            </div>
        </div>

        <div class="p-6 space-y-6">

            {{-- 📦 หมวดที่ 1: ข้อมูลรถยนต์และคนขับ --}}
            <div class="flex flex-col sm:flex-row gap-5 bg-blue-50/50 dark:bg-slate-900/50 rounded-xl p-5 border border-blue-100 dark:border-slate-700/50 transition-colors print-box">
                <div class="w-full sm:w-32 h-24 rounded-lg bg-white dark:bg-slate-800 overflow-hidden shrink-0 border border-gray-200 dark:border-slate-700 relative">
                    @if($booking->car && $booking->car->pic)
                        <img src="{{ asset('img/' . $booking->car->pic) }}" class="w-full h-full object-cover" alt="Car Image">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-50 dark:bg-slate-800"><span class="text-xs text-gray-400">ไม่มีรูปภาพ</span></div>
                    @endif
                </div>

                <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-4 content-center">
                    <div>
                        <span class="block text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase">รถยนต์ที่จัดสรร</span>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white leading-tight mt-1">{{ $booking->car->car_name ?? 'ยังไม่ระบุรถยนต์' }}</h4>
                        <div class="inline-block mt-1 text-xs font-medium text-gray-600 dark:text-slate-300 bg-white dark:bg-slate-800 px-2 py-0.5 rounded border border-gray-200 dark:border-slate-600 font-mono">
                            ทะเบียน: {{ $booking->car->license_plate ?? '-' }}
                        </div>
                    </div>
                    <div class="sm:border-l border-blue-200 dark:border-slate-700 sm:pl-5">
                        <span class="block text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase">พนักงานขับรถ</span>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-1">
                            {{ $booking->driver ? $booking->driver->first_name . ' ' . $booking->driver->last_name : 'ผู้จองขับเอง / ยังไม่ระบุ' }}
                        </h4>
                        @if($booking->driver && $booking->driver->phone)
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">📞 {{ $booking->driver->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 📦 หมวดที่ 2: วันและเวลา --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border border-gray-100 dark:border-slate-700 p-4 rounded-xl bg-gray-50/30 dark:bg-slate-800/30 print-box">
                    <div class="text-[11px] font-bold text-blue-500 uppercase tracking-wide">วัน-เวลา เริ่มเดินทาง (ไป)</div>
                    <div class="text-base font-bold text-gray-900 dark:text-slate-200 mt-1">
                        {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Bangkok')->format('j') }} {{ $thMonthFull[\Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Bangkok')->format('n')] }} {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Bangkok')->addYears(543)->format('Y') }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-slate-400 mt-0.5">
                        เวลา {{ \Carbon\Carbon::parse($booking->start_time)->timezone('Asia/Bangkok')->format('H:i') }} น.
                    </div>
                </div>
                <div class="border border-gray-100 dark:border-slate-700 p-4 rounded-xl bg-gray-50/30 dark:bg-slate-800/30 print-box">
                    <div class="text-[11px] font-bold text-rose-500 uppercase tracking-wide">วัน-เวลา สิ้นสุด (กลับ)</div>
                    <div class="text-base font-bold text-gray-900 dark:text-slate-200 mt-1">
                        {{ \Carbon\Carbon::parse($booking->end_time)->timezone('Asia/Bangkok')->format('j') }} {{ $thMonthFull[\Carbon\Carbon::parse($booking->end_time)->timezone('Asia/Bangkok')->format('n')] }} {{ \Carbon\Carbon::parse($booking->end_time)->timezone('Asia/Bangkok')->addYears(543)->format('Y') }}
                    </div>
                    <div class="text-sm text-gray-600 dark:text-slate-400 mt-0.5">
                        เวลา {{ \Carbon\Carbon::parse($booking->end_time)->timezone('Asia/Bangkok')->format('H:i') }} น.
                    </div>
                </div>
            </div>

            {{-- 📦 หมวดที่ 3: ข้อมูลปลายทางและผู้จอง --}}
            <div class="space-y-4 pt-2">
                <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 border-b border-gray-50 dark:border-slate-700/50 pb-4">
                    <span class="w-32 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase shrink-0 pt-0.5">ผู้ทำรายการจอง</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-slate-200">{{ $booking->user->name ?? 'ไม่ระบุชื่อ' }} <span class="text-gray-500 dark:text-slate-400 font-normal text-xs ml-2">(จำนวนผู้เดินทาง: {{ $booking->passenger_count }} ท่าน)</span></span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 border-b border-gray-50 dark:border-slate-700/50 pb-4">
                    <span class="w-32 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase shrink-0 pt-0.5">สถานที่ปลายทาง</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-slate-200">📍 {{ $booking->destination }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 pb-2">
                    <span class="w-32 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase shrink-0 pt-0.5">วัตถุประสงค์</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-slate-300 leading-relaxed">{{ $booking->purpose }}</span>
                </div>
            </div>

            {{-- 📦 หมวดที่ 4: หมายเหตุจากหัวหน้า --}}
            @if($booking->head_remark)
            <div class="mt-4 p-4 rounded-xl border flex gap-3 print-box {{ $status === 'rejected' ? 'bg-[#fff1f2] border-[#fecdd3] text-[#be123c]' : 'bg-[#fefce8] border-[#fde047] text-[#b45309]' }}">
                <div class="shrink-0 mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold mb-1 uppercase tracking-wide">ความเห็นจากผู้อนุมัติ</h4>
                    <p class="text-sm font-medium leading-relaxed">{{ $booking->head_remark }}</p>
                </div>
            </div>
            @endif

            {{-- 🖋️ หมวดที่ 5: ลายเซ็นแบบ Digital 100% (แสดงเฉพาะในโหมดปริ้นท์) --}}
            <div class="hidden print-signature mt-16 pb-8">
                <div class="flex justify-between px-2 sm:px-12">
                    
                    {{-- ✍️ ฝั่งซ้าย: ผู้ขออนุญาต --}}
                    <div class="text-center w-64">
                        <p class="text-sm font-bold text-gray-900">( {{ $booking->user->name ?? 'ไม่ระบุชื่อ' }} )</p>
                        <p class="text-[12px] text-gray-800 mt-1 font-bold">ผู้ขออนุญาตใช้รถยนต์</p>
                        
                        {{-- 🛡️ Badge ยืนยัน Digital --}}
                        <div class="inline-block bg-gray-100 border border-gray-200 rounded px-3 py-1 mt-2.5 mb-2 print-bg-gray">
                            <p class="text-[10px] text-gray-600 font-medium flex items-center gap-1.5">
                                <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                ทำรายการผ่านระบบอิเล็กทรอนิกส์
                            </p>
                        </div>

                        <p class="text-[12px] text-gray-800 font-mono leading-relaxed mt-1">
                            วันที่จอง: {{ \Carbon\Carbon::parse($booking->created_at)->timezone('Asia/Bangkok')->format('d/m/') }}{{ \Carbon\Carbon::parse($booking->created_at)->timezone('Asia/Bangkok')->addYears(543)->format('Y') }}
                        </p>
                    </div>

                    {{-- ✍️ ฝั่งขวา: ผู้อนุมัติ / หัวหน้า --}}
                    <div class="text-center w-64">
                        @if($status !== 'pending' && $booking->approver)
                            <p class="text-sm font-bold text-gray-900">( {{ $booking->approver->name }} )</p>
                            <p class="text-[12px] text-gray-800 mt-1 font-bold">ผู้อนุมัติ / หัวหน้างาน</p>
                            
                            {{-- 🛡️ Badge ยืนยัน Digital --}}
                            <div class="inline-block bg-gray-100 border border-gray-200 rounded px-3 py-1 mt-2.5 mb-2 print-bg-gray">
                                <p class="text-[10px] text-gray-600 font-medium flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    อนุมัติผ่านระบบอิเล็กทรอนิกส์
                                </p>
                            </div>

                            <p class="text-[12px] text-gray-800 font-mono leading-relaxed mt-1">
                                วันที่อนุมัติ: {{ \Carbon\Carbon::parse($booking->updated_at)->timezone('Asia/Bangkok')->format('d/m/') }}{{ \Carbon\Carbon::parse($booking->updated_at)->timezone('Asia/Bangkok')->addYears(543)->format('Y') }}
                            </p>
                        @else
                            {{-- ถ้าระบบยังรอพิจารณา --}}
                            <p class="text-sm font-bold text-gray-400 italic">( รอการพิจารณา )</p>
                            <p class="text-[12px] text-gray-400 mt-1 font-bold">ผู้อนุมัติ / หัวหน้างาน</p>
                            
                            <div class="inline-block border border-gray-100 rounded px-3 py-1 mt-2.5 mb-2">
                                <p class="text-[10px] text-gray-400 font-medium flex items-center gap-1.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    รอการพิจารณาผ่านระบบ
                                </p>
                            </div>

                            <p class="text-[12px] text-gray-400 font-mono leading-relaxed mt-1">วันที่อนุมัติ: -</p>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection