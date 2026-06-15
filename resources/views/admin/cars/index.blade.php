@extends('layouts.app')

@section('title', 'จัดการรถยนต์ | Admin')
@section('header', 'จัดการข้อมูลรถยนต์')

@section('content')
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden transition-colors duration-300">
    
    <div class="p-6 border-b border-gray-50 dark:border-slate-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 transition-colors">
        <div class="shrink-0">
            <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100 transition-colors">รายการรถยนต์ทั้งหมด</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 transition-colors">จัดการข้อมูลรถยนต์ ยี่ห้อ ทะเบียน และสถานะการใช้งาน</p>
        </div>
        
        {{-- 🔍 ส่วนค้นหา และ ปุ่มเพิ่มรถยนต์ --}}
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            
            {{-- ฟอร์มค้นหา --}}
            <form action="{{ route('admin.cars.index') }}" method="GET" class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อรถ, ทะเบียน..." 
                       class="w-full pl-10 pr-8 py-2.5 text-sm border border-gray-200 dark:border-slate-600 rounded-xl bg-gray-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/50 transition-colors shadow-sm">
                
                {{-- ไอคอนแว่นขยาย --}}
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-slate-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                {{-- ปุ่ม X ล้างการค้นหา --}}
                @if(request('search'))
                    <a href="{{ route('admin.cars.index') }}" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors" title="ล้างการค้นหา">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                @endif
            </form>

            <a href="{{ route('admin.cars.create') }}" 
               class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm flex items-center justify-center whitespace-nowrap shrink-0">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                เพิ่มรถยนต์ใหม่
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-slate-700/50 text-gray-600 dark:text-slate-300 text-xs uppercase tracking-wider transition-colors">
                    <th class="py-4 px-6 font-bold w-24 text-center">รูปรถ</th> 
                    <th class="py-4 px-6 font-bold w-1/4">ชื่อรถภายใน</th>
                    <th class="py-4 px-6 font-bold w-1/5">หมายเลขทะเบียน</th>
                    <th class="py-4 px-6 font-bold w-1/5">ยี่ห้อ / สี</th>
                    <th class="py-4 px-6 font-bold text-center w-1/6">สถานะ</th>
                    <th class="py-4 px-6 font-bold text-center w-auto">จัดการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 transition-colors">
                @forelse($cars as $car)
                <tr class="hover:bg-gray-50/80 dark:hover:bg-slate-700/30 transition-colors group">
                    
                    <td class="py-4 px-6 text-center">
                        @if($car->pic)
                            <div class="w-16 h-16 mx-auto bg-gray-100 dark:bg-slate-700 rounded-lg overflow-hidden border border-gray-200 dark:border-slate-600 flex items-center justify-center relative group cursor-pointer transition-colors"
                                onclick="zoomCarImage(event, '{{ asset('img/' . $car->pic) }}', '{{ $car->car_name }} ({{ $car->license_plate }})')">
                                
                                <img src="{{ asset('img/' . $car->pic) }}" 
                                    alt="{{ $car->car_name }}" 
                                    class="w-full h-full object-cover group-hover:opacity-75 transition-opacity">
                                
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                </div>
                                
                            </div>
                        @else
                            <div class="w-16 h-16 bg-gray-100 dark:bg-slate-700 rounded-lg flex items-center justify-center text-[10px] text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-slate-600 mx-auto transition-colors">ไม่มีรูป</div>
                        @endif
                    </td>
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="font-semibold text-gray-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $car->car_name }}
                        </span>
                    </td>
                    
                    <td class="py-4 px-6 whitespace-nowrap">
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded text-sm font-mono border border-gray-200 dark:border-slate-600 transition-colors">
                            {{ $car->license_plate }}
                        </span>
                    </td>
                    
                    <td class="py-4 px-6 text-sm text-gray-500 dark:text-slate-400 whitespace-nowrap transition-colors">
                        {{ $car->brand ?? '-' }} <span class="mx-1 text-gray-300 dark:text-slate-600">|</span> {{ $car->color ?? '-' }}
                    </td>
                    
                    <td class="py-4 px-6 text-center whitespace-nowrap">
                        @if($car->status == 'available')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 dark:bg-emerald-900/30 text-green-700 dark:text-emerald-400 border border-green-100 dark:border-emerald-800/50 transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 dark:bg-emerald-400 mr-2"></span>
                                พร้อมใช้งาน
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-800/50 transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 dark:bg-red-400 mr-2"></span>
                                ส่งซ่อม
                            </span>
                        @endif
                    </td>
                    
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                            <a href="{{ route('admin.cars.edit', $car->id) }}" 
                               class="flex items-center justify-center px-3 py-2 border border-blue-200 dark:border-blue-800/50 text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg text-xs font-bold transition-colors shadow-sm w-full">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                แก้ไข
                            </a>

                            <form action="{{ route('admin.cars.destroy', $car->id) }}" method="POST" class="w-full">
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
                    <td colspan="6" class="py-8 text-center text-gray-500 dark:text-slate-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            <p>ไม่พบข้อมูลรถยนต์ที่ค้นหา</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 bg-gray-50/50 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-700 transition-colors">
        {{-- ✨ จำค่าการค้นหาเวลาเปลี่ยนหน้า --}}
        {{ $cars->appends(request()->query())->links() }}
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/car-zoom.js') }}?v={{ time() }}"></script> 
@endpush
@endsection