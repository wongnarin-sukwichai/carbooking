<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ | CarBooking</title>
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
                <a href="{{ route('main') }}" class="auth-header-icon" style="text-decoration: none; cursor: pointer; display: inline-flex; transition: transform 0.2s ease-in-out;" onmouseover="this.style.transform='scale(1.1) rotate(-3deg)'" onmouseout="this.style.transform='scale(1) rotate(0)'" title="กลับหน้าหลัก">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.08 3.11H5.77L6.85 7zM5 15.5c-.83 0-1.5-.67-1.5-1.5S4.17 12.5 5 12.5s1.5.67 1.5 1.5S5.83 15.5 5 15.5zm14 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                    </svg>
                </a>
                <h1>CarBooking</h1>
                <p>สำนักวิทยบริการ มหาวิทยาลัยมหาสารคาม</p>
            </div>

            {{-- Body --}}
            <div class="auth-body">
                <p class="auth-subtitle">เข้าสู่ระบบด้วยบัญชีองค์กร</p>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif

                {{-- Google Sign-In Button --}}
                <div style="display: flex; flex-direction: column; align-items: center; gap: 1.25rem; margin-top: 0.5rem;">

                    <a href="{{ route('auth.google.redirect') }}"
                       style="display: flex; align-items: center; justify-content: center; gap: 12px; width: 100%; padding: 12px 20px; background-color: #ffffff; border: 1.5px solid #dadce0; border-radius: 8px; font-size: 1rem; font-weight: 600; color: #3c4043; text-decoration: none; transition: background-color 0.2s, box-shadow 0.2s; box-shadow: 0 1px 3px rgba(0,0,0,0.08);"
                       onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.15)';"
                       onmouseout="this.style.backgroundColor='#ffffff'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.08)';">
                        {{-- Google Logo SVG --}}
                        <svg width="22" height="22" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                            <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                            <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                            <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                            <path fill="none" d="M0 0h48v48H0z"/>
                        </svg>
                        เข้าสู่ระบบด้วย Google (@msu.ac.th)
                    </a>

                    <p style="font-size: 0.8rem; color: #94a3b8; text-align: center; line-height: 1.5;">
                        อนุญาตเฉพาะบัญชีอีเมลองค์กร <strong>@msu.ac.th</strong> เท่านั้น<br>
                        และต้องได้รับสิทธิ์จากผู้ดูแลระบบก่อน
                    </p>
                </div>

                <div style="margin-top: 1.75rem; border-top: 1px solid #e2e8f0; padding-top: 1.25rem; text-align: center;">
                    <a href="{{ route('main') }}" class="auth-back" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; color: #64748b; text-decoration: none; font-size: 0.9rem; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#64748b'">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        กลับสู่หน้าหลักเพื่อดูปฏิทิน
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
</body>
</html>
