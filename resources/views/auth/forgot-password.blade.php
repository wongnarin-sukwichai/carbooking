<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลืมรหัสผ่าน | CarBooking</title>
    {{-- ✨ ส่วนของ Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/auth-theme.css') }}?v={{ time() }}">
</head>
<body>
<div class="auth-bg">
    <div class="auth-glow2"></div>

    <div class="auth-wrap">
        <div class="auth-card">

            {{-- Header --}}
            <div class="auth-header">
                <div class="auth-header-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1>ลืมรหัสผ่าน</h1>
                <p>CarBooking — สำนักวิทยบริการ มมส.</p>
            </div>

            {{-- Body --}}
            <div class="auth-body">
                <p class="auth-desc">
                    กรอกอีเมลของคุณ เพื่อรับลิงก์สำหรับตั้งรหัสผ่านใหม่
                </p>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group">
                        <div class="form-item">
                            <label class="form-label">อีเมลของคุณ</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   placeholder="example@msu.ac.th"
                                   class="form-input" required autofocus>
                        </div>
                        <button type="submit" class="btn-submit">ส่งลิงก์ตั้งรหัสผ่านใหม่</button>
                    </div>
                </form>

                <div class="form-divider"></div>

                <a href="{{ route('login') }}" class="auth-back">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    กลับไปหน้าเข้าสู่ระบบ
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>