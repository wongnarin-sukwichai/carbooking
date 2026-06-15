@extends('layouts.app')

@section('title', 'จัดการผู้ใช้งาน | Admin')
@section('header', 'จัดการข้อมูลผู้ใช้งานระบบ')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
    <div class="p-6 border-b border-gray-50 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-colors">
        <div class="shrink-0">
            <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100 transition-colors">รายชื่อผู้ใช้งานทั้งหมด</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 transition-colors">จัดการข้อมูลบุคลากร สิทธิ์การใช้งาน และรหัสผ่าน</p>
        </div>
        
        {{-- 🔍 ส่วนค้นหา และ ปุ่มเพิ่มผู้ใช้งาน --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            
            {{-- ฟอร์มค้นหา --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ หรือ อีเมล..." 
                       class="w-full pl-10 pr-8 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-colors shadow-sm">
                
                {{-- ไอคอนแว่นขยาย --}}
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                {{-- ปุ่ม X ล้างการค้นหา (โชว์เฉพาะตอนพิมพ์ค้นหาแล้ว) --}}
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors" title="ล้างการค้นหา">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>

            <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center justify-center whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                เพิ่มผู้ใช้งานใหม่
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[700px] table-fixed">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-slate-700/50 text-gray-600 dark:text-slate-300 text-xs uppercase tracking-wider transition-colors">
                    <th class="py-4 px-6 font-bold w-[30%]">ชื่อ - นามสกุล</th>
                    <th class="py-4 px-6 font-bold w-[30%]">อีเมล</th>
                    <th class="py-4 px-6 font-bold text-center w-[20%]">ระดับสิทธิ์ (Role)</th>
                    <th class="py-4 px-6 font-bold text-center w-[20%]">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
                
                {{-- เช็คว่ามีข้อมูลหรือไม่ (เผื่อค้นหาแล้วไม่เจอ) --}}
                @forelse($users as $user)
                <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/30 transition-colors group">
                    <td class="py-4 px-6 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-semibold text-gray-800 dark:text-slate-200 transition-colors">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap text-sm text-gray-600 dark:text-slate-400 transition-colors">
                        {{ $user->email }}
                    </td>
                    <td class="py-4 px-6 text-center whitespace-nowrap">
                        @if($user->role === 'admin')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 transition-colors">ผู้ดูแลระบบ</span>
                        @elseif($user->role === 'staff_head')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 transition-colors">หัวหน้างาน</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-emerald-900/30 text-green-700 dark:text-emerald-400 transition-colors">เจ้าหน้าที่</span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                               class="flex items-center justify-center w-20 py-1.5 border border-yellow-200 dark:border-yellow-800/50 text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/40 rounded-lg text-xs font-bold transition-colors">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                แก้ไข
                            </a>
                            
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block m-0">
                                @csrf @method('DELETE')
                                <button type="button" class="flex items-center justify-center w-20 py-1.5 border border-red-200 dark:border-red-800/50 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg text-xs font-bold transition-colors btn-delete">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    ลบ
                                </button>
                            </form>
                            @else
                            <span class="flex items-center justify-center w-20 py-1.5 text-[10px] text-gray-400 dark:text-slate-500 italic bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-100 dark:border-slate-600 font-medium transition-colors">
                                บัญชีของคุณ
                            </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500 dark:text-slate-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <p>ไม่พบข้อมูลผู้ใช้งานที่ค้นหา</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-700 transition-colors">
        {{-- ✨ การใช้ appends(request()->query()) จะช่วยให้เวลาเปลี่ยนหน้า 2, 3 คำค้นหาจะไม่หายไป --}}
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection