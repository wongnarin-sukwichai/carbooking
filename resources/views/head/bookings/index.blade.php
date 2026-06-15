@extends('layouts.app')

@section('title', 'รายการจองทั้งหมด | Head')
@section('header', 'คิวการจองรถยนต์ทั้งหมด')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
@endpush

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
    
    {{-- 🔍 Filter ค้นหา --}}
    <div class="px-6 py-4 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <form action="{{ url()->current() }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-end">
            
            <div class="w-full lg:w-1/4">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">🚘 เลือกรถยนต์</label>
                <select name="car_id" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50 hover:bg-white dark:bg-slate-900 dark:hover:bg-slate-800 dark:text-slate-200">
                    <option value="">-- รถยนต์ทั้งหมด --</option>
                    @foreach($cars as $c)
                        <option value="{{ $c->id }}" {{ request('car_id') == $c->id ? 'selected' : '' }}>{{ $c->car_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full lg:w-1/4">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">👨‍✈️ เลือกคนขับรถ</label>
                <select name="driver_id" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition bg-gray-50 hover:bg-white dark:bg-slate-900 dark:hover:bg-slate-800 dark:text-slate-200">
                    <option value="">-- คนขับทั้งหมด --</option>
                    @foreach($drivers as $d)
                        <option value="{{ $d->id }}" {{ request('driver_id') == $d->id ? 'selected' : '' }}>{{ $d->first_name }} {{ $d->last_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- ✨ เพิ่มช่องค้นหาข้อความตรงนี้ --}}
            <div class="w-full lg:w-1/3">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">🔍 พิมพ์ค้นหา</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           class="w-full pl-10 pr-4 py-2.5 bg-gray-50 hover:bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition text-gray-800 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500" 
                           placeholder="ชื่อผู้จอง, ปลายทาง, วัตถุประสงค์...">
                </div>
            </div>

            <div class="flex gap-2 w-full lg:w-auto">
                <button type="submit" class="w-full lg:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition flex items-center justify-center">
                    ค้นหา
                </button>
                @if(request('car_id') || request('driver_id') || request('search'))
                    <a href="{{ url()->current() }}" class="px-5 py-2.5 bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-lg transition flex items-center justify-center whitespace-nowrap">
                        ล้างค่า
                    </a>
                @endif
            </div>
        </form>
    </div>
        
    {{-- Header ตาราง & ปุ่ม Export --}}
    <div class="p-6 border-b border-gray-50 dark:border-slate-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/30 dark:bg-slate-700/30 transition-colors">
        <div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100 transition-colors">ประวัติการจองและพิจารณาคิวรถทั้งหมด</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 transition-colors">ตรวจสอบรายละเอียด หรือแก้ไขคิวงานที่ต้องการได้ที่นี่</p>
        </div>
        
        <button onclick='openExportModal("{{ route("bookings.export") }}", "{{ csrf_token() }}", @json($drivers))' 
                class="flex items-center bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition-all shadow-sm shrink-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export รายงาน (Excel)
        </button>
    </div>

    {{-- ตารางข้อมูล --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[900px] table-fixed">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-slate-800/50 text-gray-600 dark:text-slate-300 text-xs uppercase tracking-wider border-b border-gray-100 dark:border-slate-700 transition-colors">
                    <th class="py-4 px-6 font-bold w-[20%]">ผู้จอง / วัตถุประสงค์</th>
                    <th class="py-4 px-6 font-bold w-[20%]">รถที่จอง / ปลายทาง</th>
                    <th class="py-4 px-6 font-bold w-[15%]">คนขับรถ</th> 
                    <th class="py-4 px-6 font-bold w-[20%]">วัน-เวลาที่เดินทาง</th>
                    <th class="py-4 px-6 font-bold text-center w-[10%]">สถานะ</th>
                    <th class="py-4 px-6 font-bold text-center w-[15%]">จัดการคิว</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/30 transition-colors group">
                    
                    <td class="py-4 px-6">
                        <div class="font-semibold text-gray-800 dark:text-slate-200 truncate transition-colors">{{ $booking->user->name ?? 'ไม่ทราบชื่อ' }}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1 truncate transition-colors" title="{{ $booking->purpose }}">{{ $booking->purpose }}</div>
                    </td>
                    
                    <td class="py-4 px-6">
                        <div onclick='showBookingDetails(@json($booking))' class="cursor-pointer group inline-block">
                            <div class="font-bold text-blue-600 dark:text-blue-400 group-hover:text-blue-800 dark:group-hover:text-blue-300 transition-colors flex items-center gap-1.5 truncate">
                                {{ $booking->car->car_name ?? 'ลบออกจากระบบแล้ว' }}
                                <svg class="w-3.5 h-3.5 text-blue-200 dark:text-blue-900/50 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-slate-400 mt-1 truncate group-hover:text-gray-700 dark:group-hover:text-slate-200 transition-colors">📍 {{ $booking->destination }}</div>
                        </div>
                    </td>

                    <td class="py-4 px-6">
                        @if($booking->driver)
                            <span class="text-sm font-bold text-gray-800 dark:text-slate-200 transition-colors">
                                {{ $booking->driver->first_name }} {{ $booking->driver->last_name }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 border border-gray-200 dark:border-slate-600 transition-colors">
                                ไม่ระบุ
                            </span>
                        @endif
                    </td>
                    
                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="text-sm text-gray-800 dark:text-slate-200 transition-colors">
                            <span class="font-medium">เริ่ม:</span> {{ \Carbon\Carbon::parse($booking->start_time)->addYears(543)->locale('th')->translatedFormat('d M Y') }} 🕒 {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} น.
                        </div>
                        <div class="text-sm text-gray-600 dark:text-slate-400 mt-1 transition-colors">
                            <span class="font-medium">ถึง:</span> {{ \Carbon\Carbon::parse($booking->end_time)->addYears(543)->locale('th')->translatedFormat('d M Y') }} 🕒 {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} น.
                        </div>
                    </td>
                    
                    <td class="py-4 px-6 text-center whitespace-nowrap">
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                'approved' => 'bg-green-100 text-green-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $statusLabels = [
                                'pending' => 'รอพิจารณา',
                                'approved' => 'อนุมัติแล้ว',
                                'rejected' => 'ไม่อนุมัติ',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800 dark:bg-slate-700 dark:text-slate-300' }} transition-colors">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>
                        
                        @if($booking->head_remark)
                            <div onclick="showFullRemark('{{ addslashes($booking->head_remark) }}')" 
                                class="text-[10px] text-gray-500 dark:text-slate-400 mt-1 truncate max-w-[120px] mx-auto cursor-pointer hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition-all" 
                                title="คลิกเพื่ออ่านหมายเหตุแบบเต็ม">
                                " {{ $booking->head_remark }} "
                            </div>
                        @endif
                    </td>
                    
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                            
                            {{-- ปุ่มแก้ไข --}}
                            <a href="{{ route(Auth::user()->role === 'admin' ? 'admin.bookings.edit' : 'head.bookings.edit', $booking->id) }}" 
                               class="flex items-center justify-center px-3 py-2 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg text-xs font-bold transition-colors shadow-sm w-full">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                จัดการ
                            </a>

                            {{-- ปุ่มลบ (ทำงานร่วมกับ global-scripts) --}}
                            <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.bookings.destroy' : 'head.bookings.destroy', $booking->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-delete flex items-center justify-center px-3 py-2 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg text-xs font-bold transition-colors shadow-sm w-full">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    ลบ
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500 dark:text-slate-400 transition-colors">
                        @if(request('search') || request('car_id') || request('driver_id'))
                            ไม่พบข้อมูลที่ตรงกับเงื่อนไขการค้นหา
                        @else
                            ไม่มีข้อมูลการจองในระบบ
                        @endif
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>

    <script src="{{ asset('js/export-manager.js') }}"></script>
    <script src="{{ asset('js/booking-details.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/remark-manager.js') }}?v={{ time() }}"></script>
@endpush
@endsection