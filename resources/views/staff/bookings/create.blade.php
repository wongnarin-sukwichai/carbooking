@extends('layouts.app')

@section('title', 'จองรถยนต์ | CarBooking')
@section('header', 'แบบฟอร์มขออนุญาตใช้รถยนต์ส่วนกลาง')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.default.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom-tom-select.css') }}?v={{ time() }}">
@endpush

@section('content')
<div class="max-w-4xl mx-auto mt-2">
    
    {{-- แจ้งเตือน Validation --}}
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50 rounded-xl text-sm shadow-sm transition-colors duration-300">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
        <div class="p-6 border-b border-gray-50 dark:border-slate-700 bg-gray-50/30 dark:bg-slate-800/50 transition-colors">
            <h2 class="text-lg font-bold text-gray-800 dark:text-slate-100 transition-colors">กรอกรายละเอียดการเดินทาง</h2>
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" class="p-6 md:p-8" onsubmit="return showLoading(this, 'กำลังส่งคำขอจองรถ...')">
            @csrf

            {{-- ส่วนพิเศษ สำหรับจองแทนผู้อื่น --}}
            @if(in_array(Auth::user()->role, ['admin', 'staff_head']))
            <div class="mb-8 p-5 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800/30 rounded-xl relative transition-colors duration-300">
                <div class="absolute top-0 right-0 p-2 opacity-10 dark:opacity-5">
                    <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                </div>
                <label class="block text-sm font-bold text-blue-800 dark:text-blue-400 mb-2 relative z-10 transition-colors">
                    <svg class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    ผู้ขอใช้รถ (โหมดจัดการโดยผู้ดูแลระบบและหัวหน้า)
                </label>
                <div class="relative z-10 text-slate-800 dark:text-slate-100">
                    <select id="user_search_select" name="user_id" placeholder="พิมพ์ชื่อเพื่อค้นหา..." class="w-full text-sm">
                        <option value="{{ Auth::id() }}">จองให้ตัวเอง (คุณ)</option>
                        @foreach($users as $u)
                            @if($u->id !== Auth::id())
                                @php
                                    $roleThai = [
                                        'admin' => 'ผู้ดูแลระบบ',
                                        'staff_head' => 'หัวหน้างาน',
                                        'staff' => 'เจ้าหน้าที่'
                                    ][$u->role] ?? 'เจ้าหน้าที่';
                                @endphp
                                <option value="{{ $u->id }}">
                                    {{ $u->name }} ({{ $roleThai }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <p class="text-xs text-blue-600 dark:text-blue-400 mt-2.5 font-medium relative z-10 transition-colors">* ระบบจะบันทึกคิวนี้ในนามของผู้ใช้งานที่คุณเลือก และจะทำการ <span class="bg-blue-100 dark:bg-blue-900/40 px-1 rounded transition-colors">อนุมัติให้อัตโนมัติ</span> ทันที</p>
            </div>
            @endif
            
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3 transition-colors">1. เลือกรถยนต์ที่ต้องการใช้งาน <span class="text-red-500">*</span></label>
                
                @if($cars->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($cars as $car)
                        <label class="flex items-center gap-4 p-4 border border-gray-200 dark:border-slate-600 rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700/50 has-[:checked]:border-blue-500 dark:has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:ring-1 has-[:checked]:ring-blue-500 dark:has-[:checked]:ring-blue-400 transition-all duration-200 h-full shadow-sm">
                            
                            <input type="radio" name="car_id" value="{{ $car->id }}" class="mt-0.5 shrink-0 w-5 h-5 text-blue-600 border-gray-300 dark:border-slate-500 focus:ring-blue-500 dark:bg-slate-800 cursor-pointer" required {{ old('car_id') == $car->id ? 'checked' : '' }}>
                            
                            <div class="flex-1">
                                <span class="block font-bold text-gray-800 dark:text-slate-200 text-base leading-snug transition-colors">
                                    {{ $car->car_name }} <span class="text-sm font-normal text-gray-500 dark:text-slate-400 ml-1 transition-colors">(ทะเบียน: {{ $car->license_plate }})</span>
                                </span>
                                <span class="block text-sm text-gray-500 dark:text-slate-400 mt-1 transition-colors">
                                    ยี่ห้อ: {{ $car->brand ?? '-' }} <span class="mx-1 text-gray-300 dark:text-slate-600">|</span> สี: {{ $car->color ?? '-' }}
                                </span>
                            </div>
                            
                            <div class="w-32 h-24 flex-shrink-0 bg-gray-100 dark:bg-slate-700 rounded-lg overflow-hidden border border-gray-200 dark:border-slate-600 flex items-center justify-center relative group transition-colors shadow-inner">
                                @if($car->pic != null)
                                    {{-- ✨ เพิ่ม relative z-20 และ pointer-events-auto เพื่อให้รูปภาพกดได้โดยไม่ติดบัค Radio Button --}}
                                    <img src="{{ asset('img/' . $car->pic) }}" 
                                            alt="{{ $car->car_name }}" 
                                            class="w-full h-full object-cover cursor-zoom-in relative z-20 pointer-events-auto group-hover:opacity-75 transition-opacity"
                                            onclick="zoomCarImage(event, '{{ asset('img/' . $car->pic) }}', '{{ $car->car_name }} ({{ $car->license_plate }})')">
                                    
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-30">
                                        <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                    </div>
                                @else
                                    <svg class="w-8 h-8 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                        </label>
                        @endforeach
                    </div>
                @else
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800/50 rounded-lg text-sm flex items-center shadow-sm transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        ขณะนี้ไม่มีรถยนต์ที่พร้อมใช้งานในระบบ
                    </div>
                @endif
            </div>

            <div class="mb-8 border-t border-gray-100 dark:border-slate-700 pt-8 transition-colors">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3 transition-colors">2. เลือกคนขับรถ <span class="text-red-500">*</span></label>
                
                @if($drivers->count() > 0)
                    <div class="relative shadow-sm w-full text-slate-800">
                        <select id="driver_select" name="driver_id" class="w-full text-sm" placeholder="-- ค้นหาหรือเลือกคนขับรถ --" required>
                            <option value="">-- ค้นหาหรือเลือกคนขับรถ --</option>
                            @foreach($drivers as $driver)
                                @php
                                    $imageUrl = $driver->pic 
                                        ? asset('img/' . $driver->pic) 
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($driver->first_name) . '&background=eff6ff&color=1d4ed8&rounded=true';
                                @endphp
                                
                                <option value="{{ $driver->id }}" 
                                        data-pic="{{ $imageUrl }}"
                                        {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                     {{ $driver->first_name }} {{ $driver->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800/50 rounded-lg text-sm flex items-center shadow-sm transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        ขณะนี้ไม่มีคนขับรถที่พร้อมให้บริการ
                    </div>
                @endif
            </div>

            <div class="mb-8 border-t border-gray-100 dark:border-slate-700 pt-8 transition-colors">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-4 transition-colors">3. วัน-เวลา ที่ต้องการใช้งาน <span class="text-red-500">*</span></label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors">เริ่มการเดินทาง</label>
                        <input type="text" id="start_time" name="start_time" value="{{ old('start_time') }}" class="bg-white dark:bg-slate-900 w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer shadow-sm text-gray-800 dark:text-slate-100 placeholder:text-gray-400 dark:placeholder:text-slate-500" placeholder="คลิกเลือกวันและเวลา..." required readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors">สิ้นสุดการเดินทาง</label>
                        <input type="text" id="end_time" name="end_time" value="{{ old('end_time') }}" class="bg-white dark:bg-slate-900 w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition cursor-pointer shadow-sm text-gray-800 dark:text-slate-100 placeholder:text-gray-400 dark:placeholder:text-slate-500" placeholder="คลิกเลือกวันและเวลา..." required readonly>
                    </div>
                </div>
            </div>

            <div class="mb-8 border-t border-gray-100 dark:border-slate-700 pt-8 transition-colors">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-4 transition-colors">4. รายละเอียดการเดินทาง <span class="text-red-500">*</span></label>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors">สถานที่ปลายทาง</label>
                        <input type="text" name="destination" value="{{ old('destination') }}" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-100 placeholder:text-gray-400 dark:placeholder:text-slate-500" placeholder="เช่น มหาวิทยาลัยขอนแก่น, ศาลากลางจังหวัด" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors">จำนวนผู้เดินทาง</label>
                        <input type="number" name="passenger_count" value="{{ old('passenger_count', 1) }}" min="1" max="20" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-100" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors">รายละเอียด / วัตถุประสงค์</label>
                    <textarea name="purpose" rows="3" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition shadow-sm bg-white dark:bg-slate-900 text-gray-800 dark:text-slate-100 placeholder:text-gray-400 dark:placeholder:text-slate-500" placeholder="ระบุเหตุผลในการขอใช้รถยนต์..." required>{{ old('purpose') }}</textarea>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-100 dark:border-slate-700 transition-colors">
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium transition-colors">* กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนกดส่งคำขอ</p>
                <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                    ส่งคำขอจองรถยนต์
                </button>
            </div>
            
        </form>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/th.js"></script>
    <script src="{{ asset('js/custom-datepicker.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/car-zoom.js') }}?v={{ time() }}"></script> 
    
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="{{ asset('js/user-select-init.js') }}?v={{ time() }}"></script>
@endpush
@endsection