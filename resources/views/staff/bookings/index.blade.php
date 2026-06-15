@extends('layouts.app')

@section('title', 'ประวัติการจองของฉัน | CarBooking')
@section('header', 'ประวัติการจองรถยนต์ของคุณ')

@section('content')
<div class="max-w-6xl mx-auto mt-2 pb-10">
    
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 dark:bg-emerald-900/30 border-l-4 border-green-500 dark:border-emerald-500 rounded-r-lg flex items-center shadow-sm transition-colors duration-300">
        <svg class="w-6 h-6 text-green-500 dark:text-emerald-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="text-green-700 dark:text-emerald-400 font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-500 rounded-r-lg flex items-center shadow-sm transition-colors duration-300">
        <svg class="w-6 h-6 text-red-500 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span class="text-red-700 dark:text-red-400 font-medium">{!! nl2br(e(session('error'))) !!}</span>
    </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b border-gray-50 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-center gap-4 transition-colors">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100 transition-colors">รายการคำขอจองรถยนต์</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 transition-colors">ติดตามสถานะการจองและดูประวัติย้อนหลังของคุณ</p>
            </div>
            <a href="{{ route('bookings.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm flex items-center justify-center group">
                <svg class="w-4 h-4 mr-2 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                จองรถเพิ่ม
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[850px] table-fixed">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-slate-700/50 text-gray-600 dark:text-slate-300 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 transition-colors">
                        <th class="py-4 px-6 font-bold w-[25%]">รถที่จอง</th>
                        <th class="py-4 px-6 font-bold w-[25%]">ปลายทาง / วัตถุประสงค์</th>
                        <th class="py-4 px-6 font-bold w-[25%]">วัน-เวลาเดินทาง</th>
                        <th class="py-4 px-6 font-bold text-center w-[25%]">สถานะ / การจัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-blue-50/50 dark:hover:bg-slate-700/50 transition-colors group relative border-b border-gray-50 dark:border-slate-700/30 last:border-0">
                        
                        <td class="py-4 px-6 whitespace-nowrap">
                            <a href="{{ route('bookings.show', $booking->id) }}" class="group/item inline-block">
                                <div class="font-bold text-blue-600 dark:text-blue-400 group-hover/item:text-blue-800 dark:group-hover/item:text-blue-300 transition-colors flex items-center gap-1.5 underline underline-offset-4 decoration-transparent group-hover/item:decoration-blue-400">
                                    {{ $booking->car->car_name ?? 'ลบออกจากระบบแล้ว' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-mono bg-gray-100 dark:bg-slate-700 px-2 py-0.5 rounded inline-block transition-colors">
                                    ทะเบียน: {{ $booking->car->license_plate ?? '-' }}
                                </div>
                            </a>
                        </td>
                        
                        <td class="py-4 px-6">
                            <div class="text-sm text-gray-800 dark:text-slate-200 font-semibold truncate transition-colors" title="{{ $booking->destination }}">📍 {{ $booking->destination }}</div>
                            <div class="text-xs text-gray-500 dark:text-slate-400 mt-1 truncate transition-colors" title="{{ $booking->purpose }}">{{ $booking->purpose }}</div>
                        </td>
                        
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="text-sm text-gray-800 dark:text-slate-200 transition-colors">
                                <span class="font-medium text-gray-500 dark:text-slate-400">ไป:</span> {{ \Carbon\Carbon::parse($booking->start_time)->addYears(543)->format('d/m/Y H:i') }}
                            </div>
                            <div class="text-sm text-gray-800 dark:text-slate-200 mt-1 transition-colors">
                                <span class="font-medium text-gray-500 dark:text-slate-400">กลับ:</span> {{ \Carbon\Carbon::parse($booking->end_time)->addYears(543)->format('d/m/Y H:i') }}
                            </div>
                        </td>
                        
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            @if($booking->status === 'pending')
                                <span class="inline-flex items-center px-3 py-1.5 bg-[#fefce8] dark:bg-yellow-900/30 text-[#b45309] dark:text-yellow-400 rounded-full text-[11px] font-bold border border-[#fde047] dark:border-yellow-800/50 shadow-sm transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1.5 animate-pulse text-[#d97706]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    รอหัวหน้าพิจารณา
                                </span>
                            @elseif($booking->status === 'approved')
                                <span class="inline-flex items-center px-3 py-1.5 bg-[#ecfdf5] dark:bg-emerald-900/30 text-[#047857] dark:text-emerald-400 rounded-full text-[11px] font-bold border border-[#a7f3d0] dark:border-emerald-800/50 shadow-sm transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-[#10b981]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    อนุมัติแล้ว
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 bg-[#fff1f2] dark:bg-rose-900/30 text-[#be123c] dark:text-rose-400 rounded-full text-[11px] font-bold border border-[#fecdd3] dark:border-rose-800/50 shadow-sm transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1.5 text-[#e11d48]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    ไม่อนุมัติ
                                </span>
                            @endif

                            @if($booking->status === 'pending')
                                <div class="mt-3 flex gap-1.5 justify-center">
                                    {{-- ✨ เปลี่ยนไอคอน: เป็นไอคอน "เปิดไฟล์ (File Text)" --}}
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="inline-flex items-center justify-center flex-1 text-[11px] font-bold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-600 hover:text-white px-2 py-2 rounded-lg transition-all group border border-blue-100 dark:border-blue-800/50 shadow-sm" title="ดูรายละเอียดแบบเต็ม">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    
                                    {{-- ปุ่ม 3: ยกเลิก/ลบ --}}
                                    <form action="{{ route('bookings.destroy_staff', $booking->id) }}" method="POST" class="flex-1 m-0" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิกคำขอจองรถคิวนี้? \n(การกระทำนี้ไม่สามารถย้อนกลับได้)');">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="w-full inline-flex items-center justify-center text-[11px] font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-600 hover:text-white px-2 py-2 rounded-lg transition-all group border border-red-100 dark:border-red-800/50 shadow-sm" title="ยกเลิกคำขอ">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="mt-3">
                                    {{-- ✨ เปลี่ยนไอคอน: เป็นไอคอน "เปิดไฟล์ (File Text)" เพื่อความต่อเนื่อง --}}
                                    <a href="{{ route('bookings.show', $booking->id) }}" class="inline-flex items-center justify-center w-[130px] text-xs font-bold text-blue-700 dark:text-blue-300 hover:text-white dark:hover:text-white transition-all duration-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-600 dark:hover:bg-blue-500 border border-blue-200 dark:border-blue-800/50 px-3 py-2 rounded-xl shadow-sm hover:shadow-md group">
                                        ดูรายละเอียด
                                        <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-500 dark:text-slate-400 transition-colors">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4 border border-gray-100 dark:border-slate-600 shadow-inner">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <p class="text-base font-medium">คุณยังไม่มีประวัติการจองรถยนต์</p>
                                <p class="text-sm text-gray-400 dark:text-slate-500 mt-1">เริ่มต้นสร้างคำขอเดินทางครั้งแรกของคุณได้เลย</p>
                                <a href="{{ route('bookings.create') }}" class="mt-4 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-bold transition-all flex items-center bg-blue-50 dark:bg-blue-900/20 px-4 py-2 rounded-lg">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    จองรถยนต์
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-700 transition-colors">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection