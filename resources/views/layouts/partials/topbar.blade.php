<header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 flex-shrink-0 z-10 transition-colors duration-300">

    {{-- Left: Hamburger + Page Title --}}
    <div class="flex items-center gap-3">
        <button id="mobile-menu-btn"
                class="p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition lg:hidden"
                aria-label="เปิดเมนู">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <h2 class="text-[20px] font-semibold text-slate-700 dark:text-slate-200 transition-colors">
            @yield('header', 'Dashboard')
        </h2>
    </div>

    {{-- Right: User info + Theme Toggle + Logout --}}
    <div class="flex items-center gap-2 sm:gap-3">

        {{-- ✨ ปุ่มสลับ Dark/Light Mode (เพิ่มกรอบ พื้นหลัง และเงาให้ดูเด่นขึ้น) --}}
        <button id="theme-toggle" type="button" 
                class="flex items-center justify-center w-9 h-9 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-slate-700 hover:border-blue-200 dark:hover:border-slate-600 focus:outline-none rounded-full transition-all duration-300 shadow-sm group mr-1 sm:mr-2" 
                title="สลับโหมดหน้าจอ">
            {{-- ไอคอนพระจันทร์ (ซ่อนตอนโหมดมืด) --}}
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 group-hover:-rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
            </svg>
            {{-- ไอคอนพระอาทิตย์ (ซ่อนตอนโหมดสว่าง) --}}
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 group-hover:rotate-45 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
            </svg>
        </button>

        {{-- User info --}}
        <div class="hidden md:flex flex-col items-end">
            <span class="text-sm font-medium text-slate-700 dark:text-slate-300 leading-tight transition-colors">
                {{ Auth::user()->name ?? 'ผู้ใช้งาน' }}
            </span>
            @php
                $roleName = match(Auth::user()->role ?? '') {
                    'admin'      => 'ผู้ดูแลระบบ',
                    'staff_head' => 'หัวหน้างาน',
                    'staff'      => 'เจ้าหน้าที่',
                    default      => 'ผู้ใช้งานทั่วไป',
                };
                // ✨ อัปเดตสี Role badge ให้รองรับโหมดมืด
                $roleColor = match(Auth::user()->role ?? '') {
                    'admin'      => 'text-violet-600 bg-violet-50 dark:bg-violet-900/30 dark:text-violet-400',
                    'staff_head' => 'text-amber-600 bg-amber-50 dark:bg-amber-900/30 dark:text-amber-400',
                    'staff'      => 'text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400',
                    default      => 'text-slate-600 bg-slate-100 dark:bg-slate-800 dark:text-slate-400',
                };
            @endphp
            <span class="text-[11px] font-medium px-1.5 py-0.5 rounded {{ $roleColor }} transition-colors leading-tight mt-0.5">
                {{ $roleName }}
            </span>
        </div>

        {{-- Avatar --}}
        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 font-semibold text-sm flex-shrink-0 transition-colors">
            {{ mb_substr(Auth::user()->name ?? 'U', 0, 1) }}
        </div>

        {{-- Divider --}}
        <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 hidden sm:block mx-1 transition-colors"></div>

        {{-- Logout --}}
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" 
                    onclick="confirmLogout()"
                    class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
                <span class="hidden sm:inline">ออกจากระบบ</span>
            </button>
        </form>

    </div>
</header>