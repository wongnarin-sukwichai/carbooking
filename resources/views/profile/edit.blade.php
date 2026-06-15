@extends('layouts.app') 

@section('title', 'แก้ไขข้อมูลส่วนตัว | ระบบจองรถยนต์')
@section('header', 'การตั้งค่าบัญชีผู้ใช้')

@section('content')
<div class="p-6 sm:p-10 space-y-6 max-w-4xl mx-auto">
    
    {{-- Header --}}
    <div class="flex flex-col gap-2 transition-colors">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-slate-100 tracking-tight transition-colors">⚙️ แก้ไขข้อมูลส่วนตัว</h1>
        <p class="text-slate-500 dark:text-slate-400 text-sm transition-colors">จัดการข้อมูลพื้นฐานและการรักษาความปลอดภัยของบัญชีคุณ</p>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3 transition-colors duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl flex items-start gap-3 transition-colors duration-300">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <ul class="text-sm font-medium list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- 📦 กล่องที่ 1: ข้อมูลทั่วไป --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col transition-colors duration-300">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 transition-colors">ข้อมูลทั่วไป</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 transition-colors">อัปเดตชื่อและอีเมลที่ใช้ติดต่อ</p>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <form action="{{ route('profile.update') }}" method="POST" class="flex-1 flex flex-col">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                        </div>

                        <div>
                            <label class="block text-[13px] font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">อีเมล <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                        </div>
                    </div>

                    <div class="pt-4 mt-auto">
                        <button type="submit" class="w-full bg-blue-600 dark:bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-500 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors shadow-sm">
                            บันทึกข้อมูล
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 📦 กล่องที่ 2: เปลี่ยนรหัสผ่าน --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col transition-colors duration-300">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 transition-colors">
                <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 transition-colors">เปลี่ยนรหัสผ่าน</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 transition-colors">ตั้งรหัสผ่านใหม่เพื่อความปลอดภัย</p>
            </div>
            
            <div class="p-6 flex-1 flex flex-col">
                <form action="{{ route('profile.update_password') }}" method="POST" class="flex-1 flex flex-col">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-[13px] font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">รหัสผ่านปัจจุบัน <span class="text-red-500">*</span></label>
                            <input type="password" name="current_password" required placeholder="••••••••"
                                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                        </div>

                        <div>
                            <label class="block text-[13px] font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">รหัสผ่านใหม่ <span class="text-red-500">*</span></label>
                            <input type="password" name="password" required placeholder="อย่างน้อย 8 ตัวอักษร"
                                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                        </div>

                        <div>
                            <label class="block text-[13px] font-medium text-slate-700 dark:text-slate-300 mb-1.5 transition-colors">ยืนยันรหัสผ่านใหม่ <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" required placeholder="พิมพ์รหัสผ่านใหม่อีกครั้ง"
                                   class="w-full px-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 focus:bg-white dark:focus:bg-slate-800 transition-colors">
                        </div>
                    </div>

                    <div class="pt-4 mt-auto">
                        <button type="submit" class="w-full bg-slate-800 dark:bg-slate-700 hover:bg-slate-900 dark:hover:bg-slate-600 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors shadow-sm">
                            อัปเดตรหัสผ่าน
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection