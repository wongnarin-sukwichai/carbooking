<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ระบบจองรถยนต์ | สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม')</title>
    {{-- ✨ ส่วนของ Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Dark Mode --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="antialiased flex flex-col min-h-screen bg-white dark:bg-slate-900 transition-colors duration-300">
    <nav id="main-nav" class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-100 dark:border-slate-800 transition-colors duration-300">
        <div class="nav-inner flex justify-between items-center max-w-7xl mx-auto px-4 sm:px-6 py-3.5">

            <a href="{{ route('main') }}" class="nav-brand flex items-center gap-3 sm:gap-4 group">
                
                {{-- ✨ 1. โลโก้หน่วยงาน (ออกแบบเป็น Badge สีขาว รองรับไฟล์ JPG) --}}
                <div class="relative shrink-0 flex items-center justify-center bg-white rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.06)] dark:shadow-[0_0_15px_rgba(255,255,255,0.08)] p-1 sm:p-1.5 transition-all duration-300 group-hover:shadow-md dark:group-hover:shadow-[0_0_20px_rgba(255,255,255,0.15)] border border-slate-100 dark:border-slate-700/50">
                    {{-- ✨ ปรับขนาดรูปลงมาให้พอดีขึ้น (h-6 sm:h-8) --}}
                    <img src="{{ asset('img/car_logo.jpg') }}" alt="โลโก้สำนักวิทยบริการ" class="h-6 sm:h-8 w-auto object-contain rounded-md mix-blend-multiply dark:mix-blend-normal">
                </div>

                {{-- ✨ เส้นคั่นบางๆ แบบ Gradient --}}
                <div class="hidden sm:block w-[1.5px] h-8 bg-gradient-to-b from-transparent via-slate-200 dark:via-slate-700 to-transparent rounded-full opacity-70 transition-colors"></div>

                {{-- ✨ 2. ชื่อระบบ (ไอคอนใหม่ + ข้อความทูโทน) --}}
                <div class="flex items-center gap-2.5 sm:gap-3">
                    {{-- โลโก้ (ไอคอนตึกสีน้ำเงิน) --}}
                    <div class="brand-icon hidden sm:flex w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-br from-blue-500 to-blue-700 rounded-[14px] items-center justify-center text-white shadow-md shadow-blue-500/30 shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-3">
                        <svg viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                            <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM5 15.5c-.83 0-1.5-.67-1.5-1.5S4.17 12.5 5 12.5s1.5.67 1.5 1.5S5.83 15.5 5 15.5zm14 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                        </svg>
                    </div>

                    {{-- ข้อความ --}}
                    <div class="brand-text flex flex-col justify-center">
                        <span class="brand-title text-lg sm:text-[22px] font-extrabold text-slate-800 dark:text-white transition-colors leading-none tracking-tight">
                            Car<span class="text-blue-600 dark:text-blue-400 transition-colors">Booking</span>
                        </span>
                        <span class="brand-sub text-[9px] sm:text-[11.5px] font-medium text-slate-500 dark:text-slate-400 transition-colors mt-0.5 tracking-wide">สำนักวิทยบริการ มมส.</span>
                    </div>
                </div>
                
            </a>

            <div class="nav-links flex items-center gap-2 md:gap-4 shrink-0">
                <a href="#calendar-section" class="nav-link hidden md:block text-sm font-medium text-gray-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400 transition-colors">ตารางคิวรถ</a>
                <a href="#how-it-works" class="nav-link hidden md:block text-sm font-medium text-gray-600 hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400 transition-colors">วิธีใช้งาน</a>
                
                {{-- ✨ ปุ่มสลับ Dark/Light Mode --}}
                <button id="theme-toggle" type="button" 
                        class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-slate-700 hover:border-blue-300 dark:hover:border-slate-600 focus:outline-none rounded-full transition-all duration-300 shadow-sm group mx-1 md:mx-2" 
                        title="สลับโหมดหน้าจอ">
                    <svg id="theme-toggle-dark-icon" class="hidden w-4 h-4 sm:w-5 sm:h-5 group-hover:-rotate-12 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                    </svg>
                    <svg id="theme-toggle-light-icon" class="hidden w-4 h-4 sm:w-5 sm:h-5 group-hover:rotate-45 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                    </svg>
                </button>

                <a href="{{ route('login') }}" class="btn-login bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl text-[13px] sm:text-sm font-bold transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-1.5 sm:gap-2 ml-1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="w-4 h-4 hidden sm:block">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    เข้าสู่ระบบ
                </a>
            </div>

        </div>
    </nav>

    {{-- เนื้อหาหลัก --}}
    <div class="flex-grow">
        @yield('content')
    </div>

    {{-- ✨ Footer ใช้ Tailwind Classes ตรงๆ เพื่อให้ Dark Mode แสดงผลได้สมบูรณ์ที่สุด --}}
    <footer class="font-sans bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 pt-16 pb-8 mt-16 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12 mb-12">

                <!-- 📍 คอลัมน์ 1: ข้อมูลติดต่อ -->
                <div class="md:col-span-12 lg:col-span-5">
                    <div class="flex items-center gap-3 mb-6">
                        {{-- ✨ อัปเดตไอคอนใน Footer --}}
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-sm shrink-0">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM5 15.5c-.83 0-1.5-.67-1.5-1.5S4.17 12.5 5 12.5s1.5.67 1.5 1.5S5.83 15.5 5 15.5zm14 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold tracking-tight text-slate-900 dark:text-white transition-colors">Car<span class="text-blue-600 dark:text-blue-400">Booking</span></span>
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-4 transition-colors" style="font-family: 'IBM Plex Sans Thai', sans-serif;">สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม</h3>
                    <ul class="space-y-4 text-[14px] leading-relaxed text-slate-600 dark:text-slate-400 transition-colors" style="font-family: 'IBM Plex Sans Thai', sans-serif;">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>ต.ขามเรียง อ.กันทรวิชัย จ.มหาสารคาม 44150</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>โทร : 0-4375-4322-40 ต่อ 2493, 2491, 2405, 2439</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            <span>แฟกซ์ : 0-4375-4358</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>อีเมล : library@msu.ac.th</span>
                        </li>
                    </ul>
                </div>

                <!-- 📍 คอลัมน์ 2: เมนูด่วน -->
                <div class="md:col-span-6 lg:col-span-3" style="font-family: 'IBM Plex Sans Thai', sans-serif;">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-6 relative inline-block transition-colors">
                        เมนูที่เกี่ยวข้อง
                        <span class="absolute -bottom-2 left-0 w-10 h-1 bg-blue-600 dark:bg-blue-500 rounded-full"></span>
                    </h3>
                    <ul class="space-y-3 text-[14px]">
                        <li><a href="https://www.facebook.com/librarymsu" target="_blank" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-2 font-medium"><span class="text-blue-500 font-bold">›</span> เฟซบุ๊กสำนักวิทยบริการ</a></li>
                        <li><a href="https://library.msu.ac.th" target="_blank" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-2 font-medium"><span class="text-blue-500 font-bold">›</span> เว็บไซต์สำนักวิทยบริการ</a></li>
                        <li><a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition flex items-center gap-2 font-medium"><span class="text-blue-500 font-bold">›</span> เข้าสู่ระบบจองรถยนต์</a></li>
                    </ul>
                </div>

                <!-- 📍 คอลัมน์ 3: สถิติผู้เข้าชมเว็บไซต์ -->
                <div class="md:col-span-6 lg:col-span-4" style="font-family: 'IBM Plex Sans Thai', sans-serif;">
                    <div class="bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-6 rounded-2xl shadow-sm relative overflow-hidden transition-colors duration-300">
                        <!-- แสงตกแต่ง -->
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-blue-100 dark:bg-blue-900/30 rounded-full blur-2xl opacity-60"></div>
                        
                        <h3 class="text-base font-bold text-slate-900 dark:text-white mb-5 flex items-center gap-2 relative z-10 transition-colors">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            สถิติผู้เข้าชมเว็บไซต์
                        </h3>
                        
                        <div class="space-y-2.5 relative z-10">
                            <div class="flex justify-between items-center px-1">
                                <span class="text-sm text-slate-500 dark:text-slate-400">วันนี้</span>
                                <span class="text-slate-900 dark:text-white font-mono font-bold text-base transition-colors">{{ number_format($visitorStats['daily'] ?? 0) }}</span>
                            </div>
                            <div class="w-full h-px bg-slate-200 dark:bg-slate-700 transition-colors"></div>
                            <div class="flex justify-between items-center px-1">
                                <span class="text-sm text-slate-500 dark:text-slate-400">เดือนนี้</span>
                                <span class="text-slate-900 dark:text-white font-mono font-bold text-base transition-colors">{{ number_format($visitorStats['monthly'] ?? 0) }}</span>
                            </div>
                            <div class="w-full h-px bg-slate-200 dark:bg-slate-700 transition-colors"></div>
                            <div class="flex justify-between items-center px-1">
                                <span class="text-sm text-slate-500 dark:text-slate-400">ปีนี้</span>
                                <span class="text-slate-900 dark:text-white font-mono font-bold text-base transition-colors">{{ number_format($visitorStats['yearly'] ?? 0) }}</span>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700 transition-colors">
                                <div class="flex justify-between items-center bg-blue-600 dark:bg-slate-700 border border-transparent dark:border-slate-600 px-4 py-3 rounded-xl shadow-sm transition-colors duration-300">
                                    <span class="text-sm font-bold text-white dark:text-slate-200">รวมทั้งหมด</span>
                                    <span class="font-mono font-bold text-xl text-white dark:text-blue-400 tracking-wider">{{ number_format($visitorStats['total'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom Copyright -->
            <div class="pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4 transition-colors" style="font-family: 'IBM Plex Sans Thai', sans-serif;">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    © {{ date('Y') }} ระบบจองรถยนต์ - สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม
                </p>
                <div class="flex gap-3">
                    <a href="https://www.facebook.com/librarymsu/" target="_blank" class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition-all text-slate-500 dark:text-slate-400" title="Facebook">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    </a>
                    <a href="https://library.msu.ac.th/" target="_blank" class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-sm flex items-center justify-center hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 transition-all text-slate-500 dark:text-slate-400" title="เว็บไซต์">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </footer>

    {{-- ✨ สลับธีม --}}
    <script src="{{ asset('js/theme-toggle.js') }}?v={{ time() }}"></script>

    @stack('scripts')
</body>
</html>