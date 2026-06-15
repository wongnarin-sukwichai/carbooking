<aside id="sidebar" class="fixed top-0 left-0 h-full z-30 w-64 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 flex flex-col transform -translate-x-full transition-all duration-300 lg:static lg:translate-x-0 border-r border-slate-200 dark:border-slate-800">
    
    {{-- Logo --}}
    <div class="h-16 flex items-center px-4 border-b border-slate-200 dark:border-slate-800 gap-3 flex-shrink-0 transition-colors duration-300">
        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
            <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="currentColor">
                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM5 15.5c-.83 0-1.5-.67-1.5-1.5S4.17 12.5 5 12.5s1.5.67 1.5 1.5S5.83 15.5 5 15.5zm14 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
            </svg>
        </div>
        <div class="min-w-0 flex flex-col justify-center">
            <p class="text-[15px] font-bold text-blue-600 dark:text-blue-400 leading-snug tracking-tight truncate transition-colors duration-300">ระบบจองรถยนต์</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 leading-snug truncate transition-colors duration-300">สำนักวิทยบริการ มมส.</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
        
        @if(auth()->check())
            
            {{-- 🎯 คำนวณจำนวนคิวรออนุมัติ (โชว์ตัวเลขแจ้งเตือน) --}}
            @php
                $pendingCount = \App\Models\Booking::where('status', 'pending')->count();
            @endphp

            {{-- 👑 Admin --}}
            @if(auth()->user()->role === 'admin')
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-3 pt-2 pb-2 transition-colors duration-300">ภาพรวม</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-medium transition-all
                          {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">🏠</span> หน้าหลัก / ปฏิทินคิวรถ
                </a>

                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-3 pt-4 pb-2 transition-colors duration-300">จัดการระบบ</p>
                <a href="{{ route('admin.cars.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                          {{ request()->routeIs('admin.cars.*') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">🚘</span> จัดการข้อมูลรถยนต์
                </a>
                <a href="{{ route('admin.drivers.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                        {{ request()->routeIs('admin.drivers.*') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">👨‍✈️</span> จัดการคนขับรถ
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                          {{ request()->routeIs('admin.users.*') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">👥</span> จัดการผู้ใช้งาน
                </a>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-3 pt-4 pb-2 transition-colors duration-300">การพิจารณา & ประวัติ</p>
                
                <a href="{{ route('admin.bookings.pending') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                          {{ request()->routeIs('admin.bookings.pending') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">⏳</span> รอการพิจารณาอนุมัติ
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                          {{ request()->routeIs('admin.bookings.index') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">📋</span> ประวัติการจองทั้งหมด
                </a>

            {{-- 👔 Staff Head --}}
            @elseif(auth()->user()->role === 'staff_head')
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-3 pt-2 pb-2 transition-colors duration-300">ภาพรวม</p>
                <a href="{{ route('head.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-medium transition-all
                          {{ request()->routeIs('head.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">🏠</span> หน้าหลัก / ปฏิทินคิวรถ
                </a>

                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-3 pt-4 pb-2 transition-colors duration-300">การพิจารณา & ประวัติ</p>
                
                <a href="{{ route('head.bookings.pending') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                          {{ request()->routeIs('head.bookings.pending') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">⏳</span> รอการพิจารณาอนุมัติ
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('head.bookings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                          {{ request()->routeIs('head.bookings.index') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">📋</span> ประวัติการจองทั้งหมด
                </a>

            {{-- 👨‍💼 Staff --}}
            @else
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-3 pt-2 pb-2 transition-colors duration-300">ภาพรวม</p>
                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] font-medium transition-all
                          {{ request()->routeIs('staff.dashboard') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                    <span class="text-base">🏠</span> หน้าหลัก / ปฏิทินคิวรถ
                </a>
            @endif

            {{-- 🌟 บริการส่วนตัว (โชว์ให้ทุกคนเห็น) --}}
            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-slate-500 px-3 pt-6 pb-2 transition-colors duration-300">บริการส่วนตัว</p>
            
            <a href="{{ route('bookings.create') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                      {{ request()->routeIs('bookings.create') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                <span class="text-base">📅</span> จองรถยนต์
            </a>

            <a href="{{ route('bookings.my_history') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all
                      {{ request()->routeIs('bookings.my_history') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                <span class="text-base">🕒</span> ประวัติการจองของฉัน
            </a>

            {{-- ✨ ส่วนที่เพิ่มใหม่: ปุ่มแก้ไขข้อมูลส่วนตัว --}}
            @if(auth()->user()->role !== 'admin')
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[14.5px] transition-all mt-1
                      {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white shadow-sm font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-slate-100' }}">
                <span class="text-base">⚙️</span> แก้ไขข้อมูลส่วนตัว
            </a>
            @endif

        @endif {{-- ปิด auth()->check() อย่างถูกต้องตรงนี้ --}}

    </nav>
    
    {{-- Footer --}}
    <div class="px-4 py-4 border-t border-slate-200 dark:border-slate-800 flex-shrink-0 transition-colors duration-300">
        <p class="text-[11px] text-slate-400 dark:text-slate-500 text-center leading-relaxed transition-colors duration-300">
            สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม
        </p>
    </div>

</aside>