@extends('layouts.app')

@section('title', 'แก้ไขรถยนต์ | Admin')
@section('header', 'แก้ไขข้อมูลรถยนต์')

@section('sidebar-menu')
    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 rounded-lg text-slate-300 hover:bg-slate-700 hover:text-white transition">🏠 ภาพรวมระบบ</a>
    <a href="{{ route('admin.cars.index') }}" class="block px-4 py-2.5 rounded-lg bg-blue-600 text-white font-medium mt-2">🚘 จัดการข้อมูลรถยนต์</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto mt-4">
    <div class="bg-white dark:bg-slate-800 p-6 md:p-10 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 transition-colors duration-300">
        
        <div class="mb-8 border-b border-gray-100 dark:border-slate-700 pb-4 transition-colors">
            <h2 class="text-xl font-bold text-gray-800 dark:text-slate-100 transition-colors">แก้ไขรายละเอียด: {{ $car->car_name }}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1 transition-colors">ปรับปรุงข้อมูลรถยนต์และสถานะการใช้งาน</p>
        </div>

        <form action="{{ route('admin.cars.update', $car->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 transition-colors">รูปรถยนต์ (ถ้าต้องการเปลี่ยน)</label>
                    
                    @if($car->pic)
                    <div class="mb-4">
                        <img src="{{ asset('img/' . $car->pic) }}" alt="Current Image" class="w-48 h-32 object-cover rounded-lg shadow-sm border border-gray-200 dark:border-slate-600 transition-colors">
                    </div>
                    @endif

                    <input type="file" name="pic" accept="image/*" 
                           class="w-full border @error('pic') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 dark:file:bg-yellow-900/30 file:text-yellow-700 dark:file:text-yellow-500 hover:file:bg-yellow-100 dark:hover:file:bg-yellow-900/50">
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5 transition-colors">ปล่อยว่างไว้หากไม่ต้องการเปลี่ยนรูปภาพ (รองรับไฟล์: jpeg, png, jpg, webp ขนาดไม่เกิน 5MB)</p>
                    @error('pic') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 transition-colors">ชื่อรถภายในองค์กร <span class="text-red-500">*</span></label>
                    <input type="text" name="car_name" value="{{ old('car_name', $car->car_name) }}" 
                           class="w-full border @error('car_name') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100" required>
                    @error('car_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 transition-colors">หมายเลขทะเบียน <span class="text-red-500">*</span></label>
                    <input type="text" name="license_plate" value="{{ old('license_plate', $car->license_plate) }}" 
                           class="w-full border @error('license_plate') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100" required>
                    @error('license_plate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 transition-colors">สถานะปัจจุบัน <span class="text-red-500">*</span></label>
                    <select name="status" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                        <option value="available" {{ old('status', $car->status) == 'available' ? 'selected' : '' }}>🟢 พร้อมใช้งาน (Available)</option>
                        <option value="maintenance" {{ old('status', $car->status) == 'maintenance' ? 'selected' : '' }}>🔴 ส่งซ่อม / บำรุงรักษา (Maintenance)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 transition-colors">ยี่ห้อ (Brand)</label>
                    <input type="text" name="brand" value="{{ old('brand', $car->brand) }}" 
                           class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2 transition-colors">สีรถ (Color)</label>
                    <input type="text" name="color" value="{{ old('color', $car->color) }}" 
                           class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                </div>
            </div>

            <div class="flex flex-col-reverse md:flex-row justify-end mt-8 gap-3 pt-6 border-t border-gray-100 dark:border-slate-700 transition-colors">
                <a href="{{ route('admin.cars.index') }}" class="w-full md:w-auto px-6 py-3 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition text-center">ยกเลิก</a>
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 dark:hover:bg-blue-500 shadow-md transition text-center">อัปเดตข้อมูลรถ</button>
            </div>
        </form>
    </div>
</div>
@endsection