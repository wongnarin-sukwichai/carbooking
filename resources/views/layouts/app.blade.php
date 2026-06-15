<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'ระบบจองรถยนต์ | สำนักวิทยบริการ มหาสารคาม')</title>
    {{-- ✨ ส่วนของ Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')

    {{-- ✨ Script ตรวจสอบ Dark Mode ทันทีที่โหลดหน้าเว็บ (ต้องเอาไว้ใน <head>) --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
{{-- ✨ เพิ่ม dark:bg-slate-900 และ dark:text-slate-200 ที่ body --}}
<body class="bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-200 antialiased transition-colors duration-300" style="font-family: 'IBM Plex Sans Thai', sans-serif;">

    <div class="flex h-screen overflow-hidden">

        {{-- Mobile overlay --}}
        <div id="sidebar-overlay" 
             class="fixed inset-0 bg-slate-900/50 dark:bg-slate-900/80 backdrop-blur-sm z-20 hidden lg:hidden transition-opacity"
             aria-hidden="true">
        </div>

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Main area --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            
            @include('layouts.partials.topbar')

            {{-- ✨ เพิ่ม dark:bg-slate-900 ที่พื้นหลังของ main --}}
            <main class="flex-1 overflow-y-auto p-6 bg-slate-50 dark:bg-slate-900 transition-colors duration-300">
                
                @include('layouts.partials.flash-messages')

                @yield('content')
                
            </main>

        </div>
    </div>
    <script src="{{ asset('js/spinner.js') }}?v={{ rand() }}"></script>

    {{-- ✨ เรียกใช้งาน Script สลับธีม --}}
    <script src="{{ asset('js/theme-toggle.js') }}?v={{ time() }}"></script>

    @stack('scripts')
    @include('layouts.partials.global-scripts')
</body>
</html>