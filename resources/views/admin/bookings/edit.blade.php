@extends('layouts.app')

@section('title', 'จัดการและแก้ไขคิวรถ | Admin')
@section('header', 'ตรวจสอบและจัดการคิวรถยนต์')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
@endpush

@section('content')
<div class="max-w-5xl mx-auto mt-2">
    
    {{-- ปุ่มย้อนกลับ --}}
    <div class="mb-6 flex items-center justify-between transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center font-bold text-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 transition-colors">จัดการข้อมูลคิวรถยนต์</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400 transition-colors">รหัสการจอง: #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="flex items-center text-sm font-bold text-gray-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors bg-white dark:bg-slate-800 px-4 py-2 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            ย้อนกลับ
        </a>
    </div>

    {{-- แจ้งเตือน Validation --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl text-sm shadow-sm transition-colors">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ฟอร์มแก้ไขข้อมูล --}}
    <form action="{{ route(Auth::user()->role === 'admin' ? 'admin.bookings.update' : 'head.bookings.update', $booking->id) }}" 
        method="POST" 
        onsubmit="return showLoading(this, 'กำลังบันทึกและส่งอีเมล...')"
        class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
          @csrf
          @method('PUT')
          <div class="p-6 md:p-8 space-y-8">
            
            {{-- 👤 1. ข้อมูลผู้จอง (ดูได้อย่างเดียว) --}}
            <div class="bg-gray-50/50 dark:bg-slate-700/30 p-5 rounded-xl border border-gray-100 dark:border-slate-600 transition-colors">
                <label class="block text-[11px] font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-3 transition-colors">ผู้ส่งคำขอจอง</label>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold transition-colors">
                        {{ mb_substr($booking->user->name, 0, 1) }}
                    </div>
                    <div>
                        <div class="text-base font-bold text-gray-800 dark:text-slate-200 transition-colors">{{ $booking->user->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-slate-400 transition-colors">จองเมื่อ: {{ \Carbon\Carbon::parse($booking->created_at)->addYears(543)->format('d/m/Y H:i') }} น.</div>
                    </div>
                </div>
            </div>

            {{-- 🚘 2. เลือกรถและคนขับ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">รถยนต์ที่ต้องการใช้ <span class="text-red-500">*</span></label>
                    <select name="car_id" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition cursor-pointer text-sm">
                        @foreach($cars as $car)
                            <option value="{{ $car->id }}" {{ old('car_id', $booking->car_id) == $car->id ? 'selected' : '' }}>
                                🚘 {{ $car->car_name }} (ทะเบียน: {{ $car->license_plate }}) 
                                {{ $car->status !== 'available' ? ' [ไม่พร้อมใช้งาน]' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">พนักงานขับรถ <span class="text-gray-400 font-normal"></span></label>
                    <select name="driver_id" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition cursor-pointer text-sm">
                        <option value="">- ไม่ระบุ / ขับเอง -</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id', $booking->driver_id) == $driver->id ? 'selected' : '' }}>
                                👨‍✈️ {{ $driver->first_name }} {{ $driver->last_name }}
                                {{ $driver->status !== 'available' ? ' [ไม่พร้อมใช้งาน]' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 🕒 3. วันเวลาและรายละเอียด --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">เวลาเริ่มต้น <span class="text-red-500">*</span></label>
                    <input type="text" id="start_time" name="start_time" data-allow-past="true"
                           value="{{ old('start_time', \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d H:i')) }}" 
                           class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 cursor-pointer shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">เวลาสิ้นสุด <span class="text-red-500">*</span></label>
                    <input type="text" id="end_time" name="end_time" data-allow-past="true"
                           value="{{ old('end_time', \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d H:i')) }}" 
                           class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 cursor-pointer shadow-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">สถานที่ปลายทาง <span class="text-red-500">*</span></label>
                    <input type="text" name="destination" value="{{ old('destination', $booking->destination) }}" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">จำนวนผู้เดินทาง (คน) <span class="text-red-500">*</span></label>
                    <input type="number" name="passenger_count" min="1" value="{{ old('passenger_count', $booking->passenger_count) }}" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2 transition-colors">รายละเอียด / วัตถุประสงค์ <span class="text-red-500">*</span></label>
                <textarea name="purpose" rows="3" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors">{{ old('purpose', $booking->purpose) }}</textarea>
            </div>

        </div>

        {{-- 🎯 4. ส่วนพิจารณาอนุมัติ --}}
        <div class="bg-slate-50 dark:bg-slate-900/50 p-6 md:p-8 border-t border-slate-200 dark:border-slate-700 transition-colors">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center transition-colors">
                    <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    ส่วนการพิจารณาอนุมัติ
                </h3>
                @if($booking->status == 'approved')
                    <span class="bg-green-100 dark:bg-emerald-900/30 text-green-700 dark:text-emerald-400 text-xs font-bold px-3 py-1 rounded-full shadow-sm transition-colors">สถานะปัจจุบัน: อนุมัติแล้ว</span>
                @endif
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-3 transition-colors">ปรับเปลี่ยนสถานะคิวรถ <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    {{-- Radio: อนุมัติ --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="approved" class="peer sr-only" {{ old('status', $booking->status) == 'approved' ? 'checked' : '' }}>
                        <div class="text-center p-4 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-xl peer-checked:bg-green-50 dark:peer-checked:bg-emerald-900/30 peer-checked:border-green-500 dark:peer-checked:border-emerald-500 peer-checked:ring-2 peer-checked:ring-green-500 dark:peer-checked:ring-emerald-500 transition-all hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm h-full">
                            <div class="text-2xl mb-1">✅</div>
                            <div class="font-bold peer-checked:text-green-700 dark:peer-checked:text-emerald-400 text-slate-700 dark:text-slate-300 transition-colors">อนุมัติให้ใช้รถ</div>
                        </div>
                    </label>
                    
                    {{-- Radio: ไม่อนุมัติ --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="rejected" class="peer sr-only" {{ old('status', $booking->status) == 'rejected' ? 'checked' : '' }}>
                        <div class="text-center p-4 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-xl peer-checked:bg-red-50 dark:peer-checked:bg-red-900/30 peer-checked:border-red-500 dark:peer-checked:border-red-500 peer-checked:ring-2 peer-checked:ring-red-500 dark:peer-checked:ring-red-500 transition-all hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm h-full">
                            <div class="text-2xl mb-1">❌</div>
                            <div class="font-bold peer-checked:text-red-700 dark:peer-checked:text-red-400 text-slate-700 dark:text-slate-300 transition-colors">ไม่อนุมัติ</div>
                        </div>
                    </label>

                    {{-- Radio: รอพิจารณา --}}
                    <label class="relative cursor-pointer">
                        <input type="radio" name="status" value="pending" class="peer sr-only" {{ old('status', $booking->status) == 'pending' ? 'checked' : '' }}>
                        <div class="text-center p-4 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 rounded-xl peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/30 peer-checked:border-yellow-500 dark:peer-checked:border-yellow-500 peer-checked:ring-2 peer-checked:ring-yellow-500 dark:peer-checked:ring-yellow-500 transition-all hover:bg-slate-50 dark:hover:bg-slate-700 shadow-sm h-full">
                            <div class="text-2xl mb-1">⏳</div>
                            <div class="font-bold peer-checked:text-yellow-700 dark:peer-checked:text-yellow-400 text-slate-700 dark:text-slate-300 transition-colors">รอพิจารณา</div>
                        </div>
                    </label>

                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 transition-colors">หมายเหตุการพิจารณา <span class="text-xs text-slate-500 dark:text-slate-400 font-normal">(ข้อความนี้จะถูกส่งแนบไปในอีเมลของผู้จอง)</span></label>
                <textarea name="head_remark" rows="3" placeholder="เช่น อนุญาตกรณีพิเศษ, ขอให้เปลี่ยนไปใช้รถตู้แทน..." class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors">{{ old('head_remark', $booking->head_remark) }}</textarea>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-6 border-t border-slate-200 dark:border-slate-700 transition-colors">
                <p class="text-xs text-slate-500 dark:text-slate-400 sm:mr-auto transition-colors">
                    * ระบบจะส่งอีเมลแจ้งเตือนผู้ใช้งานอัตโนมัติหากมีการเปลี่ยนสถานะเป็น อนุมัติ/ไม่อนุมัติ
                </p>
                <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white font-bold rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                  บันทึกการพิจารณาคิวรถ
              </button>
            </div>
        </div>
    </form>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script src="{{ asset('js/custom-datepicker.js') }}?v={{ time() }}"></script>
@endpush
@endsection