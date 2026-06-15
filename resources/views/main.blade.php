@extends('layouts.public')

@section('title', 'หน้าหลัก | ระบบจองรถยนต์ สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/front-theme.css') }}?v={{ time() }}">
@endpush

@section('content')
<main>

    {{-- ══════════════════════════════════════════════
         ABOVE-THE-FOLD: Hero (left) + Calendar (right)
    ══════════════════════════════════════════════ --}}
    <section class="above-fold">
        <div class="hero-bg-grid"></div>
        <div class="hero-glow g1"></div>
        <div class="hero-glow g2"></div>

        <div class="fold-inner">

            {{-- LEFT: hero --}}
            <div class="fold-left">

                <div class="hero-eyebrow">
                    <span class="live-dot"></span>
                    <span>ระบบพร้อมใช้งาน</span>
                </div>

                <h1 class="hero-title">
                    ระบบจองรถยนต์<br/>
                    <span class="title-accent">สำนักวิทยบริการ มมส.</span>
                </h1>

                <p class="hero-desc">
                    สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม<br/>
                    MSU Academic Resource Center 
                </p>

                <a href="{{ route('login') }}" class="btn-primary-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    เข้าสู่ระบบเพื่อจองรถ
                </a>

                {{-- Stats 2x2 grid --}}
                <div class="stats-grid">
                    <div class="stat-box">
                        <p class="stat-num">{{ $stats['cars'] ?? 0 }}</p>
                        <p class="stat-label">รถพร้อมให้บริการ</p>
                    </div>
                    <div class="stat-box">
                        <p class="stat-num emerald">{{ $stats['bookings'] ?? 0 }}</p>
                        <p class="stat-label">อนุมัติแล้ว</p>
                    </div>
                    <div class="stat-box">
                        <p class="stat-num amber">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="stat-label">รออนุมัติ</p>
                    </div>
                    <div class="stat-box">
                        <p class="stat-num">{{ $stats['users'] ?? 0 }}</p>
                        <p class="stat-label">ผู้ใช้งานระบบ</p>
                    </div>
                </div>

            </div>

            {{-- RIGHT: calendar --}}
            <div class="fold-right" id="calendar-section">
                <div class="calendar-card">
                    <div class="calendar-toolbar">
                        <div class="toolbar-left">
                            <span class="live-dot green"></span>
                            <span class="live-label">LIVE</span>
                            <span class="live-sub">ปฏิทินคิวรถยนต์</span>
                        </div>
                        <div class="legend-group">
                            <span class="legend-item"><span class="legend-dot emerald"></span>อนุมัติแล้ว</span>
                            <span class="legend-item"><span class="legend-dot amber"></span>รอพิจารณา</span>
                        </div>
                    </div>
                    <div id="calendar" data-events-url="{{ route('api.calendar.events') }}"></div>
                </div>
            </div>

        </div>
    </section>

    {{-- ══════════════════════════════════════════════
         HOW IT WORKS
    ══════════════════════════════════════════════ --}}
    <section class="section-steps" id="how-it-works">
        <div class="steps-inner">
            <div class="section-header">
                <div class="section-badge">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    วิธีใช้งาน
                </div>
                <h2 class="section-title">จองรถ <span class="text-accent">4 ขั้นตอน</span></h2>
                <p class="section-desc">ง่ายและรวดเร็ว ใช้เวลาไม่กี่นาทีก็จองรถเสร็จแล้ว</p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-num">01</div>
                    <div class="step-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    </div>
                    <h3>เข้าสู่ระบบ</h3>
                    <p>ใช้บัญชีบุคลากร เข้าสู่ระบบด้วย Email และ Password ของสำนักวิทยบริการ</p>
                </div>
                <div class="step-arrow"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg></div>
                <div class="step-card">
                    <div class="step-num">02</div>
                    <div class="step-icon emerald">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3>เลือกวันและรถ</h3>
                    <p>ดูตารางว่างในปฏิทิน เลือกรถที่ต้องการ ระบุวัน-เวลา และจุดหมายปลายทาง</p>
                </div>
                <div class="step-arrow"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg></div>
                <div class="step-card">
                    <div class="step-num">03</div>
                    <div class="step-icon amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3>ส่งคำขอจอง</h3>
                    <p>กรอกรายละเอียดและวัตถุประสงค์การเดินทาง แล้วส่งคำขอให้ผู้ดูแลระบบพิจารณา</p>
                </div>
                <div class="step-arrow"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/></svg></div>
                <div class="step-card">
                    <div class="step-num">04</div>
                    <div class="step-icon blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3>รับการแจ้งเตือน</h3>
                    <p>รับอีเมลยืนยันทันทีเมื่อคำขอได้รับการอนุมัติ พร้อมรายละเอียดครบถ้วน</p>
                </div>
            </div>
        </div>
    </section>
    

   

</main>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/calendar-init.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/front-scripts.js') }}?v={{ time() }}"></script>
@endpush