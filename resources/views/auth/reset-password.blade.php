<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตั้งรหัสผ่านใหม่ | CarBooking</title>
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
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1>ตั้งรหัสผ่านใหม่</h1>
                <p>CarBooking — สำนักวิทยบริการ มมส.</p>
            </div>

            {{-- Body --}}
            <div class="auth-body">
                <p class="auth-desc">กำหนดรหัสผ่านใหม่สำหรับบัญชีของคุณ</p>

                @if ($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-group">

                        {{-- Email (readonly) --}}
                        <div class="form-item">
                            <label class="form-label">อีเมลของคุณ</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $request->email) }}"
                                   class="form-input" readonly>
                        </div>

                        {{-- New password --}}
                        <div class="form-item">
                            <label class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" name="password"
                                   placeholder="••••••••"
                                   class="form-input" required autofocus>
                        </div>

                        {{-- Confirm --}}
                        <div class="form-item">
                            <label class="form-label">ยืนยันรหัสผ่านใหม่อีกครั้ง</label>
                            <input type="password" name="password_confirmation"
                                   placeholder="••••••••"
                                   class="form-input" required>
                        </div>

                        <button type="submit" class="btn-submit">บันทึกรหัสผ่านใหม่</button>

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