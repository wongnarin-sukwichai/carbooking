@extends('layouts.app')

@section('title', 'เพิ่มผู้ใช้งาน | Admin')
@section('header', 'เพิ่มข้อมูลผู้ใช้งานระบบ')

@section('content')
<div class="max-w-3xl mx-auto mt-4">
    <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 transition-colors duration-300">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1 transition-colors">ชื่อ - นามสกุล <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border @error('name') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors" required>
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1 transition-colors">
                        อีเมล <span class="text-red-500">*</span>
                        <span class="text-xs font-normal text-gray-400 dark:text-slate-500">(ต้องเป็น @msu.ac.th เท่านั้น)</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="example@msu.ac.th" class="w-full border @error('email') border-red-500 @else border-gray-300 dark:border-slate-600 @enderror rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors" required>
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1 transition-colors">ระดับสิทธิ์ (Role) <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full border border-gray-300 dark:border-slate-600 rounded-lg px-4 py-2.5 outline-none focus:ring-2 focus:ring-blue-500 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors">
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>พนักงานทั่วไป (Staff)</option>
                        <option value="staff_head" {{ old('role') == 'staff_head' ? 'selected' : '' }}>หัวหน้างาน (Staff Head)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>ผู้ดูแลระบบ (Admin)</option>
                    </select>
                </div>

                <div class="bg-blue-50 dark:bg-slate-700/50 rounded-lg p-4 flex gap-3 items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-blue-700 dark:text-slate-300">
                        ผู้ใช้งานจะเข้าระบบด้วย <strong>Google OAuth</strong> โดยใช้บัญชี @msu.ac.th ระบบจะดึงชื่อและรูปโปรไฟล์จาก Google โดยอัตโนมัติเมื่อเข้าสู่ระบบครั้งแรก
                    </p>
                </div>
            </div>

            <div class="flex justify-end mt-8 gap-3 pt-6 border-t border-gray-100 dark:border-slate-700 transition-colors">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">ยกเลิก</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 dark:bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 dark:hover:bg-blue-500 transition-colors shadow-sm">บันทึกข้อมูล</button>
            </div>
        </form>
    </div>
</div>
@endsection
